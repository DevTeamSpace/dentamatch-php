<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\JobAppliedStatus;
use App\Enums\JobType;
use App\Enums\SeekerVerifiedStatus;
use App\Helpers\JobsHelper;
use App\Http\Controllers\Controller;
use App\Models\JobSeekerProfiles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use App\Models\RecruiterJobs;
use App\Models\SavedJobs;
use App\Models\JobLists;
use App\Models\UserProfile;
use App\Models\SearchFilter;
use App\Models\Notification;
use App\Models\ChatUserLists;
use App\Models\JobseekerTempHired;
use App\Models\User;

class SearchApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['ApiAuth', 'ApiLog']);
    }

    /**
     * Description : Search Jobs
     * Method : postSearchJobs
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postSearchJobs(Request $request)
    {
        $this->validate($request, [
            'page' => 'required'
        ]);
        $userId = $request->apiUserId;
        $reqData = $request->all();
        $reqData['city'] = "";
        SearchFilter::createFilter($userId, $reqData);

        $reqData['userId'] = $userId;
        $searchResult = RecruiterJobs::searchJob($reqData);
        if (count($searchResult['list']) > 0) {
            $userData = User::getUser($userId);
            $searchResult['isJobSeekerVerified'] = isset($userData['is_job_seeker_verified']) ? $userData['is_job_seeker_verified'] : null;
            $searchResult['profileCompleted'] = isset($userData['profile_completed']) ? $userData['profile_completed'] : null;
            return ApiResponse::successResponse(trans("messages.job_search_list"), $searchResult);
        }
        return ApiResponse::noDataResponse();
    }

    /**
     * Description : Saved Unsaved a particular job with latest status
     * Method : postSaveUnsaveJob
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postSaveUnsaveJob(Request $request)
    {
        $this->validate($request, [
            'jobId'  => 'required',
            'status' => 'required',
        ]);
        $userId = $request->apiUserId;
        $reqData = $request->all();
        if ($reqData['status'] == 1) {
            $isSaved = SavedJobs::where('recruiter_job_id', '=', $reqData['jobId'])->where('seeker_id', '=', $userId)->count();
            if ($isSaved > 0) {
                $message = trans("messages.job_already_saved");
            } else {
                $saveJobs = ['recruiter_job_id' => $reqData['jobId'], 'seeker_id' => $userId];
                SavedJobs::insert($saveJobs);
                $message = trans("messages.save_job_success");
            }
        } else {
            SavedJobs::where('seeker_id', '=', $userId)->where('recruiter_job_id', '=', $reqData['jobId'])->forceDelete();
            $message = trans("messages.unsave_job_success");
        }

        return ApiResponse::successResponse($message);
    }

    /**
     * Description : Apply for a Job
     * Method : postApplyJob
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postApplyJob(Request $request)
    {
        $this->validate($request, [
            'jobId' => 'required',
        ]);
        $userId = $request->apiUserId;
        $reqData = $request->all();
        $profileComplete = UserProfile::select(['is_completed', 'is_job_seeker_verified', 'is_fulltime', 'is_parttime_monday', 'is_parttime_tuesday', 'is_parttime_wednesday', 'is_parttime_thursday', 'is_parttime_friday', 'is_parttime_saturday', 'is_parttime_sunday'])->where('user_id', $userId)->first();

        if ($profileComplete->is_completed == 1) {
            if ($profileComplete->is_job_seeker_verified != SeekerVerifiedStatus::APPROVED) {
                return ApiResponse::errorResponse(trans("messages.jobseeker_not_verified"));
            }

            $jobExists = RecruiterJobs::leftJoin('job_lists', function ($query) use ($userId) {
                $query->on('job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                    ->where('job_lists.seeker_id', '=', $userId)
                    ->whereIn('job_lists.applied_status', [JobAppliedStatus::INVITED, JobAppliedStatus::APPLIED]);
            })->where('recruiter_jobs.id', '=', $reqData['jobId'])
                ->first();
            if (!empty($jobExists) && $jobExists->applied_status == JobAppliedStatus::INVITED) {
                JobLists::where('id', $jobExists->id)->update(['applied_status' => JobAppliedStatus::APPLIED]);
                $this->notifyAdmin($reqData['jobId'], $userId, Notification::JOBSEEKERAPPLIED);
                $response = ApiResponse::successResponse(trans("messages.apply_job_success"));
            } else if (!empty($jobExists) && $jobExists->applied_status == JobAppliedStatus::APPLIED) {
                $response = ApiResponse::successResponse(trans("messages.job_already_applied"));
            } else if (($jobExists->job_type == 1 && $profileComplete->is_fulltime == 1) ||
                ($jobExists->job_type == 2 && $profileComplete->is_parttime_monday == $jobExists->is_monday && $jobExists->is_monday == 1) ||
                ($jobExists->job_type == 2 && $profileComplete->is_parttime_tuesday == $jobExists->is_tuesday && $jobExists->is_tuesday == 1) ||
                ($jobExists->job_type == 2 && $profileComplete->is_parttime_wednesday == $jobExists->is_wednesday && $jobExists->is_wednesday == 1) ||
                ($jobExists->job_type == 2 && $profileComplete->is_parttime_thursday == $jobExists->is_thursday && $jobExists->is_thursday == 1) ||
                ($jobExists->job_type == 2 && $profileComplete->is_parttime_friday == $jobExists->is_friday && $jobExists->is_friday == 1) ||
                ($jobExists->job_type == 2 && $profileComplete->is_parttime_saturday == $jobExists->is_saturday && $jobExists->is_saturday == 1) ||
                ($jobExists->job_type == 2 && $profileComplete->is_parttime_sunday == $jobExists->is_sunday && $jobExists->is_sunday == 1)) {
                $applyJobs = ['seeker_id' => $userId, 'recruiter_job_id' => $reqData['jobId'], 'applied_status' => JobAppliedStatus::APPLIED];
                JobLists::insert($applyJobs);
                $this->notifyAdmin($reqData['jobId'], $userId, Notification::JOBSEEKERAPPLIED);
                $response = ApiResponse::successResponse(trans("messages.apply_job_success"));
            } else {
                $response = ApiResponse::errorResponse(trans("messages.set_availability"));
            }
        } else {
            $response = ApiResponse::errorResponse(trans("messages.profile_not_complete"));
        }

        return $response;
    }

    /**
     * Description : Cancel a job
     * Method : postCancelJob
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postCancelJob(Request $request)
    {
        $this->validate($request, [
            'jobId'        => 'required',
            'cancelReason' => 'required',
        ]);
        $userId = $request->apiUserId;
        $reqData = $request->all();
        $jobExists = JobLists::select('id')->where('seeker_id', '=', $userId)->where('recruiter_job_id', '=', $reqData['jobId'])
            ->whereIn('applied_status', [JobAppliedStatus::SHORTLISTED, JobAppliedStatus::APPLIED, JobAppliedStatus::HIRED])->first();
        if ($jobExists) {
            $jobExists->applied_status = JobAppliedStatus::CANCELLED;
            $jobExists->cancel_reason = $reqData['cancelReason'];
            $jobExists->save();
            //delete from temp hired jobs
            JobseekerTempHired::where('jobseeker_id', $userId)->where('job_id', $reqData['jobId'])->forceDelete();
            $this->notifyAdminForCancelJob($reqData['jobId'], $userId, $reqData['cancelReason']);
            $response = ApiResponse::successResponse(trans("messages.job_cancelled_success"));
        } else {
            $response = ApiResponse::errorResponse(trans("messages.job_not_applied_by_you"));
        }

        return $response;
    }

    /**
     * Description : get list of jobs (saved, applied or shortlisted)
     * Method : getJobList
     * formMethod : GET
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function getJobList(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'page' => 'required',
        ]);
        $userId = $request->apiUserId;
        $reqData = $request->all();
        $reqData['userId'] = $userId;
        if ($reqData['type'] == 1) {
            $searchResult = SavedJobs::listSavedJobs($reqData);
            $message = trans("messages.saved_job_list");
        } else {
            $searchResult = JobLists::listJobsByStatus($reqData);
            if ($reqData['type'] == 2) {
                $message = trans("messages.applied_job_list");
            } else {
                $message = trans("messages.shortlisted_job_list");
            }
        }
        if (count($searchResult['list']) > 0) {
            return ApiResponse::successResponse($message, $searchResult);
        }

        return ApiResponse::noDataResponse();
    }

    /**
     * Description : get details of job
     * POST jobs/job-detail
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postJobDetail(Request $request)
    {
        $this->validate($request, [
            'jobId' => 'required'
        ]);
        $userId = $request->apiUserId;
        $reqData = $request->all();
        $jobId = $reqData['jobId'];

        $data = RecruiterJobs::getJobDetail($jobId, $userId);
        if (!empty($data)) {
            $data['is_applied'] = JobLists::isJobApplied($jobId, $userId);
            $data['is_saved'] = SavedJobs::getJobSavedStatus($jobId, $userId);
            return ApiResponse::successResponse(trans('messages.job_detail_success'), $data);
        }

        return ApiResponse::errorResponse(trans("messages.job_not_exists"));
    }

    /**
     * Description : Accept or reject job of any type
     * POST users/acceptreject-job
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postAcceptRejectInvitedJob(Request $request)
    {
        $this->validate($request, [
            'notificationId' => 'required|integer',
            'acceptStatus'   => 'required|integer',
            'jobDates'       => 'sometimes|array',
            'jobDates.*'     => 'date|date_format:Y-m-d',
        ]);
        $userId = $request->apiUserId;

        $notificationDetails = Notification::whereId($request->input('notificationId'))->where('receiver_id', $userId)->firstOrFail();
        $jobDetails = RecruiterJobs::findOrFail($notificationDetails->job_list_id);

        /** @var JobLists $jobInvitation */
        $jobInvitation = $jobDetails->applications()->where('seeker_id', $userId)
            ->orderBy('id', SORT_DESC)->first();

        if (!$jobInvitation)
            return ApiResponse::errorResponse(trans("messages.not_invited_job"));

        if ($jobInvitation->applied_status != JobAppliedStatus::INVITED) {
            switch ($jobInvitation->applied_status) {
                case JobAppliedStatus::HIRED:
                    $msg = trans("messages.seeker_already_hired");
                    break;
                default:
                    $msg = trans("messages.seeker_already_cancelled");
            }
            return ApiResponse::errorResponse($msg);
        }

        $jobSeeker = JobSeekerProfiles::whereUserId($userId)->firstOrFail();

        $acceptStatus = $request->input('acceptStatus');

        if ($acceptStatus == 0) {
            $response = $this->acceptRejectJob($acceptStatus, $notificationDetails, $jobInvitation);
        } else {
            if ($jobDetails->job_type == JobType::FULLTIME || $jobDetails->job_type == JobType::PARTTIME) {
                if (JobsHelper::seekerFitsJob($jobSeeker, $jobDetails)) {
                    $response = $this->acceptRejectJob($acceptStatus, $notificationDetails, $jobInvitation);
                } else {
                    return ApiResponse::errorResponse(trans("messages.set_availability"));
                }
            } else {
                $wantedDates = $request->input('jobDates', []);

                $seekerCanWorkOnDates = $jobSeeker->tempDates()
                    ->whereIn('temp_job_date', $wantedDates)
                    ->whereNotIn('temp_job_date', $jobSeeker->tempJobsHired()->select('job_date')->getQuery())
                    ->pluck('temp_job_date')->toArray();

                $jobAvailableDatesForSeeker = $jobDetails->tempJobDates()
                    ->whereIn('job_date', $seekerCanWorkOnDates)
                    ->whereNotIn('job_date',
                        $jobDetails->hiredDates()->groupBy('job_date')
                            ->havingRaw('count(id) = ?', [$jobDetails->no_of_jobs])
                            ->select('job_date')->getQuery())
                    ->pluck('job_date');

                $hiringDates = $jobAvailableDatesForSeeker->map(function ($date) use ($userId, $notificationDetails) {
                    return ['jobseeker_id' => $userId, 'job_id' => $notificationDetails->job_list_id, 'job_date' => $date];
                })->toArray();

                if (!$hiringDates) {
                    return ApiResponse::errorResponse(trans("messages.mismatch_availability"));
                }

                JobseekerTempHired::insert($hiringDates);
                $response = $this->acceptRejectJob($acceptStatus, $notificationDetails, $jobInvitation, 1, $jobAvailableDatesForSeeker);
            }
        }

        return $response;
    }

    /**
     * Description : update job status
     * @param $acceptStatus
     * @param Notification $notification
     * @param JobLists $jobInvitation
     * @param int $hired
     * @param array $dates
     * @return Response
     */
    protected function acceptRejectJob($acceptStatus, $notification, $jobInvitation, $hired = 0, $dates = [])
    {
        if ($acceptStatus == 0) {
            $jobInvitation->applied_status = JobAppliedStatus::CANCELLED;
            $msg = trans("messages.job_cancelled_success");
        } else if ($hired == 1) {
            $jobInvitation->applied_status = JobAppliedStatus::HIRED;
            ChatUserLists::firstOrCreate(['recruiter_id' => $notification->sender_id, 'seeker_id' => $notification->receiver_id]);
            $msg = trans("messages.job_hired_success");
        } else {
            $jobInvitation->applied_status = JobAppliedStatus::APPLIED;
            $msg = trans("messages.job_hired_success");
        }
        $jobInvitation->save();
        $notification->update(['seen' => 1]);
        $this->notifyAdmin($notification->job_list_id, $notification->receiver_id,
            $acceptStatus ? Notification::JOBSEEKERACCEPTED : Notification::JOBSEEKERREJECTED);

        $response = ApiResponse::successResponse($msg, $dates);
        return $response;
    }

    /**
     * Description : create notification on job status update
     * Method : notifyAdmin
     * @param int $jobId
     * @param int $senderId
     * @param int $notificationType
     */
    protected function notifyAdmin($jobId, $senderId, $notificationType)
    {
        $receiverDetails = RecruiterJobs::join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_templates.job_title_id', '=', 'job_titles.id')
            ->select(['job_templates.user_id', 'job_titles.jobtitle_name'])
            ->where('recruiter_jobs.id', $jobId)->first();
        $jobseekerDetails = UserProfile::getUserProfile($senderId);
        if ($notificationType == Notification::JOBSEEKERAPPLIED) {
            $message = $jobseekerDetails['first_name'] . ' ' . $jobseekerDetails['last_name'] . ' has applied for <b><a href="/job/details/' . $jobId . '" >' . $receiverDetails->jobtitle_name . '</a></b>';
        } else if ($notificationType == Notification::JOBSEEKERACCEPTED) {
            $message = $jobseekerDetails['first_name'] . ' ' . $jobseekerDetails['last_name'] . ' has accepted for <b><a href="/job/details/' . $jobId . '" >' . $receiverDetails->jobtitle_name . '</a></b>';
        } else if ($notificationType == Notification::JOBSEEKERREJECTED) {
            $message = $jobseekerDetails['first_name'] . ' ' . $jobseekerDetails['last_name'] . ' has rejected for <b><a href="/job/details/' . $jobId . '" >' . $receiverDetails->jobtitle_name . '</a></b>';
        }
        $notificationDetails = ['image' => $jobseekerDetails['profile_pic'], 'message' => $message];
        $data = ['receiver_id' => $receiverDetails->user_id, 'job_list_id' => $jobId, 'sender_id' => $senderId, 'notification_data' => json_encode($notificationDetails), 'notification_type' => $notificationType];
        Notification::createNotification($data);
    }

    /**
     * Description : create notification on job status cancel
     * Method : notifyAdminForCancelJob
     * @param int $jobId
     * @param int $senderId
     * @param $cancelReason
     */
    protected function notifyAdminForCancelJob($jobId, $senderId, $cancelReason)
    {
        $receiverDetails = RecruiterJobs::join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_templates.job_title_id', '=', 'job_titles.id')
            ->select(['job_templates.user_id', 'job_titles.jobtitle_name'])
            ->where('recruiter_jobs.id', $jobId)->first();
        $jobseekerDetails = UserProfile::getUserProfile($senderId);

        $message = $jobseekerDetails['first_name'] . ' ' . $jobseekerDetails['last_name'] . ' has cancelled for <b><a href="/job/details/' . $jobId . '" >' . $receiverDetails->jobtitle_name . '</b></a>';

        $notificationDetails = ['image' => $jobseekerDetails['profile_pic'], 'message' => $message, 'cancel_reason' => $cancelReason];
        $data = ['receiver_id' => $receiverDetails->user_id, 'job_list_id' => $jobId, 'sender_id' => $senderId, 'notification_data' => json_encode($notificationDetails), 'notification_type' => Notification::JOBSEEKERCANCELLED];
        Notification::createNotification($data);
    }

}
