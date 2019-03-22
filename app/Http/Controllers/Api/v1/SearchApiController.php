<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\JobAppliedStatus;
use App\Enums\JobType;
use App\Enums\SeekerVerifiedStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use App\Models\RecruiterJobs;
use App\Models\SavedJobs;
use App\Models\JobLists;
use App\Models\UserProfile;
use App\Models\SearchFilter;
use App\Models\Notification;
use App\Models\ChatUserLists;
use App\Models\JobSeekerTempAvailability;
use App\Models\TempJobDates;
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
     * Description : Update job status
     * Method : postAcceptRejectInvitedJob
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postAcceptRejectInvitedJob(Request $request)
    {
        $this->validate($request, [
            'notificationId' => 'required',
            'acceptStatus'   => 'required',
        ]);
        $userId = $request->apiUserId;
        $reqData = $request->all();
        $notificationDetails = Notification::where('id', $reqData['notificationId'])->first();
        $jobDetails = RecruiterJobs::where('recruiter_jobs.id', $notificationDetails->job_list_id)->first();

        if ($jobDetails->job_type == JobType::FULLTIME || $jobDetails->job_type == JobType::PARTTIME) {
            $seekerDetails = UserProfile::where('user_id', $userId)->first();
            if ($reqData['acceptStatus'] == 0 ||
                ($jobDetails->job_type == JobType::FULLTIME && $seekerDetails->is_fulltime == 1) ||
                ($jobDetails->job_type == JobType::PARTTIME &&
                    (($jobDetails->is_monday == 1 && $seekerDetails->is_parttime_monday == 1) ||
                        ($jobDetails->is_tuesday == 1 && $seekerDetails->is_parttime_tuesday == 1) ||
                        ($jobDetails->is_wednesday == 1 && $seekerDetails->is_parttime_wednesday == 1) ||
                        ($jobDetails->is_thursday == 1 && $seekerDetails->is_parttime_thursday == 1) ||
                        ($jobDetails->is_friday == 1 && $seekerDetails->is_parttime_friday == 1) ||
                        ($jobDetails->is_saturday == 1 && $seekerDetails->is_parttime_saturday == 1) ||
                        ($jobDetails->is_sunday == 1 && $seekerDetails->is_parttime_sunday == 1)))) {
                $response = $this->acceptRejectJob($userId, $notificationDetails->job_list_id, $reqData['acceptStatus'], $notificationDetails->sender_id, $reqData['notificationId'], 0);
            } else {
                return ApiResponse::errorResponse(trans("messages.set_availability"));
            }
        } else {
            if ($reqData['acceptStatus'] == 1) {
                // job seeker availability for temp job
                $tempAvailability = JobSeekerTempAvailability::select('temp_job_date')->where('user_id', '=', $userId)->get();
                $tempJobDates = [];
                if ($tempAvailability) {
                    $tempAvailabilityArray = $tempAvailability->toArray();
                    foreach ($tempAvailabilityArray as $value) {
                        $tempJobDates[] = $value['temp_job_date'];
                    }
                }

                $tempDates = TempJobDates::where('recruiter_job_id', $notificationDetails->job_list_id)->get()->toArray();
                $insertDates = [];
                if ($tempDates) {
                    foreach ($tempDates as $tempDate) {
                        if (in_array($tempDate['job_date'], $tempJobDates)) {
                            $insertDates[] = $tempDate['job_date'];
                        }
                    }
                }
                if (empty($insertDates)) {
                    return ApiResponse::errorResponse(trans("messages.set_availability"));
                }
                // no of dates user is available wrt to the temp job dates
                $userAvail = count($insertDates);
                // check if job seeker is already hired for any temp job for these dates
                $tempAvailability = JobseekerTempHired::where('jobseeker_id', $userId)->select('job_date')->get();

                if ($tempAvailability) {
                    $tempDate = $tempAvailability->toArray();
                    if (!empty($insertDates) && !empty($tempDate)) {
                        foreach ($tempDate as $value) {
                            if (in_array($value['job_date'], $insertDates)) {
                                $insertDates = array_diff($insertDates, [$value['job_date']]);
                            }
                        }
                    }
                }

                //no of dates user is available wrt to the temp job dates except the hired dates
                $hiredAval = count($insertDates);

                if (!empty($insertDates)) {
                    $countHiredJobs = JobseekerTempHired::where('job_id', $notificationDetails->job_list_id)
                        ->whereIn('job_date', $insertDates)
                        ->select(['job_date', DB::raw("count(id) as job_count")])
                        ->groupby('job_date')->get();
                    $countJobArray = $countHiredJobs->toArray();

                    if (!empty($countJobArray)) {
                        $hiredJobDates = [];
                        $hiredJobDateAfterCount = [];
                        foreach ($countJobArray as $value) {
                            $hiredJobDateAfterCount[] = $value['job_date'];
                            if ($value['job_count'] < $jobDetails->no_of_jobs) {
                                $hiredJobDates[] = ['jobseeker_id' => $userId, 'job_id' => $notificationDetails->job_list_id, 'job_date' => $value['job_date']];
                            }
                        }

                        $remainingHiredDate = array_diff($insertDates, $hiredJobDateAfterCount);

                        if (!empty($remainingHiredDate)) {
                            foreach ($remainingHiredDate as $value) {
                                $hiredJobDates[] = ['jobseeker_id' => $userId, 'job_id' => $notificationDetails->job_list_id, 'job_date' => $value];
                            }
                        }

                        if (!empty($hiredJobDates)) {
                            JobseekerTempHired::insert($hiredJobDates);
                            $response = $this->acceptRejectJob($userId, $notificationDetails->job_list_id, $reqData['acceptStatus'], $notificationDetails->sender_id, $reqData['notificationId']);
                        } else {
                            $response = ApiResponse::errorResponse(trans("messages.not_job_exists"));
                        }
                    } else {
                        foreach ($insertDates as $insertDate) {
                            $hiredJobDates[] = ['jobseeker_id' => $userId, 'job_id' => $notificationDetails->job_list_id, 'job_date' => $insertDate];
                        }
                        JobseekerTempHired::insert($hiredJobDates);
                        $response = $this->acceptRejectJob($userId, $notificationDetails->job_list_id, $reqData['acceptStatus'], $notificationDetails->sender_id, $reqData['notificationId']);
                    }
                } else {
                    if ($userAvail == $hiredAval) {
                        $response = ApiResponse::errorResponse(trans("messages.set_availability"));
                    } else {
                        $response = ApiResponse::errorResponse(trans("messages.mismatch_availability"));
                    }
                }
            } else {
                $response = $this->acceptRejectJob($userId, $notificationDetails->job_list_id, $reqData['acceptStatus'], $notificationDetails->sender_id, $reqData['notificationId']);
            }
        }

        return $response;
    }

    /**
     * Description : update job status
     * Method : acceptRejectJob
     * @param int $userId
     * @param int $jobId
     * @param int $acceptstatus
     * @param int $recruiterId
     * @param int $notificationId
     * @param int $hired
     * @return Response
     */
    protected function acceptRejectJob($userId, $jobId, $acceptstatus, $recruiterId, $notificationId, $hired = 1)
    {
        $jobExists = JobLists::where('seeker_id', '=', $userId)
            ->where('recruiter_job_id', '=', $jobId)
            ->orderBy('id', 'desc')
            ->first();

        if ($jobExists) {
            if ($jobExists->applied_status == JobAppliedStatus::INVITED) {
                if ($acceptstatus == 0) {
                    $jobExists->applied_status = JobAppliedStatus::CANCELLED;
                    $msg = trans("messages.job_cancelled_success");
                } else if ($hired == 1) {
                    $jobExists->applied_status = JobAppliedStatus::HIRED;
                    $userChat = new ChatUserLists();
                    $userChat->recruiter_id = $recruiterId;
                    $userChat->seeker_id = $userId;
                    $userChat->checkAndSaveUserToChatList();
                    $msg = trans("messages.job_hired_success");
                } else {
                    $jobExists->applied_status = JobAppliedStatus::APPLIED;
                    $msg = trans("messages.job_hired_success");
                }
                $jobExists->save();
                Notification::where('id', $notificationId)->update(['seen' => 1]);
                if ($acceptstatus == 0) {
                    $this->notifyAdmin($jobId, $userId, Notification::JOBSEEKERREJECTED);
                } else {
                    $this->notifyAdmin($jobId, $userId, Notification::JOBSEEKERACCEPTED);
                }
                $response = ApiResponse::successResponse($msg);
            } else {
                if ($jobExists->applied_status == JobAppliedStatus::HIRED) {
                    $msg = trans("messages.seeker_already_hired");
                } else {
                    $msg = trans("messages.seeker_already_cancelled");
                }
                $response = ApiResponse::errorResponse($msg);
            }
        } else {
            $response = ApiResponse::errorResponse(trans("messages.not_invited_job"));
        }
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
