<?php

namespace App\Utils;

use App\Enums\ActionCategory;
use App\Enums\ActionType;
use App\Enums\JobAppliedStatus;
use App\Models\ActionEntryMeta;
use App\Models\ActionLog;
use App\Models\JobLists;
use App\Models\JobTitles;
use App\Models\PreferredJobLocation;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ActionLogUtils
{
    /**
     * @param  Request  $request
     * @param  Response $response
     * @return mixed
     */
    public static function logSimpleApiRequest($request, $response) {
        $eventMeta = self::getActionTypeByApiPath($request->getPathInfo());
        if (!$eventMeta)
            return;

        $responseData = @json_decode($response->getContent(), true);
        if (!$responseData)
            return;

        $status = array_get($responseData, 'status');

        if ($status != 1)
            return;

        $entry = new ActionLog();
        $entry->category = ActionCategory::Seeker;
        $entry->user_id = array_get($responseData, 'result.userDetails.id', $request->apiUserId);
        $entry->type = $eventMeta->type;

        if ($eventMeta->requestFields) {
            $requestDataFiltered = $request->all($eventMeta->requestFields);
            $entry->request_data = json_encode($requestDataFiltered);
        }

        $responseDataFiltered = array_column(array_map(function($field) use ($responseData){
            return [
                array_last(explode('.', $field)),
                array_get($responseData, $field)
            ];
        }, $eventMeta->responseFields), 1, 0);
        $entry->response_data = json_encode($responseDataFiltered);

        $entry->save();
    }

    public static function logApplyForAJob($jobId, $userId, $fromInvite = false) {
        $requestData = [];
        if ($fromInvite)
            $requestData['from_invite'] = 1;

        self::logAction(ActionType::SeekerApplied, ActionCategory::Seeker,
            $userId, $requestData, null, $jobId);
    }

    public static function logCancelJob($jobId, $userId, $reason) {
        $requestData = ['reason' => $reason];

        self::logAction(ActionType::SeekerCancelled, ActionCategory::Seeker,
            $userId, $requestData, null, $jobId);
    }

    public static function logEmailVerification($userGroup, $userId) {
        $category = $userGroup == UserGroup::JOBSEEKER? ActionCategory::Seeker : ActionCategory::Recruiter;
        self::logAction(ActionType::EmailVerification, $category, $userId);
    }

    public static function logSeekerSearch(Request $request) {
        $requestData = [];
        $requestData['page'] = $request->input('page');

        if($request->input('jobTitle')) {
            $requestData['jobTitle'] =
                JobTitles::findMany($request->input('jobTitle'), ['jobtitle_name'])
                    ->implode('jobtitle_name', ',');
        }

        if($request->input('preferredJobLocationId')) {
            $requestData['preferredJobLocation'] =
                PreferredJobLocation::findMany($request->input('preferredJobLocationId'),
                    ['preferred_location_name'])->toArray();
        }

        if($request->input('isFulltime')) {
            $requestData['isFulltime'] = 1;
        }

        if($request->input('isParttime')) {
            $requestData['isParttime'] = 1;
        }

        if($request->input('parttimeDays')) {
            $requestData['parttimeDays'] = $request->input('parttimeDays');
        }

       self::logAction(ActionType::ApiSearch, ActionCategory::Seeker,
           $request->apiUserId, $requestData);

    }

    public static function logSeekerProfileUpdated($userId, $changes) {
        $requestData = Arr::only($changes, ['state', 'license_number', 'first_name', 'last_name',
                                            'preferred_job_location_id', 'job_titile_id']);
        if ($requestData) {
            if ($locationId = array_get($requestData, 'preferred_job_location_id'))
                $requestData['preferred_location'] = PreferredJobLocation::find($locationId)->preferred_location_name;

            if ($titleId = array_get($requestData, 'job_titile_id'))
                $requestData['job_title'] = JobTitles::find($titleId)->jobtitle_name;

            $requestData = Arr::except($requestData, ['preferred_job_location_id', 'job_titile_id']);

            self::logAction(ActionType::SeekerProfileUpdated, ActionCategory::Seeker,
                $userId, $requestData, null);
        }
    }

    public static function logRecruiterLogin($userId) {
        self::logAction(ActionType::UserLogin, ActionCategory::Recruiter, $userId);
    }

    public static function logRecruiterSignup($userId) {
        self::logAction(ActionType::UserSignUp, ActionCategory::Recruiter, $userId);
    }

    public static function logRecruiterPostJob($jobId) {
        self::logAction(ActionType::RecruiterPostJob, ActionCategory::Recruiter, Auth::user()->id,
            null, null, $jobId);
    }

    public static function logJobListStatus(JobLists $jobList) {
        $type = self::getActionTypeByStatus($jobList->applied_status);
        die($type);
        if (!$type)
            return;

        self::logAction($type, ActionCategory::Recruiter, Auth::user()->id,
            null, null, $jobList->recruiter_job_id);
    }

    private static function logAction($type, $category, $userId, $requestData = null, $responseData = null, $jobId = null) {
        $entry = new ActionLog();
        $entry->type = $type;
        $entry->category = $category;
        $entry->user_id = $userId;
        if ($requestData)
            $entry->request_data = json_encode($requestData);
        if ($responseData)
            $entry->response_data = json_encode($responseData);
        if ($jobId)
            $entry->job_id = $jobId;

        $entry->save();
    }


    /**
     * @param $requestUrl
     * @return ActionEntryMeta|null
     */
    private static function getActionTypeByApiPath($requestUrl) {
        $prefix = '/api/v1/';

        switch ($requestUrl) {
            case $prefix . 'users/sign-up':
                return new ActionEntryMeta(ActionType::UserSignUp, ['deviceType'],
                    ['result.userDetails.email']);

            case $prefix . 'users/sign-in':
                return new ActionEntryMeta(ActionType::UserLogin, [],
                    ['result.userDetails.email', 'result.userDetails.firstName', 'result.userDetails.lastName']);
        }
        return null;
    }

    /**
     * @param $requestUrl
     * @return string|null
     */
    private static function getActionTypeByStatus($appliedStatus) {
        switch (intval($appliedStatus)) {
            case JobAppliedStatus::INVITED:
                return ActionType::RecruiterInvite;
            case JobAppliedStatus::HIRED:
                return ActionType::RecruiterHire;
            case JobAppliedStatus::CANCELLED:
                return ActionType::RecruiterCancel;
        }
    }
}

