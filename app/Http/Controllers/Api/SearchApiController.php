<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\apiResponse;
use App\Models\RecruiterJobs;
use App\Models\Location;
use App\Models\SavedJobs;
use App\Models\JobLists;
use App\Models\UserProfile;
use App\Models\SearchFilter;
use App\Models\Notification;
use App\Models\ChatUserLists;
use Log;

class SearchApiController extends Controller {
    
    public function __construct() {
        $this->middleware('ApiAuth');
        $this->middleware('xss');
    }
    
    /**
     * Description : Search JObs
     * Method : postSearchjobs
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postSearchjobs(Request $request){
        try{
            $this->validate($request, [
                'lat' => 'required',
                'lng' => 'required',
                'zipCode' => 'required',
                'page' => 'required',
                'jobTitle' => 'required',
                'isFulltime' => 'required',
                'isParttime' => 'required',
                
            ]);
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                
                SearchFilter::createFilter($userId, $reqData);
                
                $location = Location::where('zipcode',$reqData['zipCode'])->first();
                if($location){
                    $reqData['userId'] = $userId;
                    $searchResult = RecruiterJobs::searchJob($reqData);
                    if(count($searchResult['list']) > 0){
                        $response = apiResponse::customJsonResponse(1, 200, trans("messages.job_search_list"),  apiResponse::convertToCamelCase($searchResult));
                    }else{
                        $response = apiResponse::customJsonResponse(0, 201, trans("messages.no_data_found"));
                    }
                }else{
                    $response = apiResponse::customJsonResponse(0, 201, trans("messages.invalid_job_location"));
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            } 
        }catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
    
    /**
     * Description : Saved Unsaved a particular job eith latest status
     * Method : postSaveUnsavejob
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postSaveUnsavejob(Request $request){
        try{
            $this->validate($request, [
                'jobId' => 'required',
                'status' => 'required',
            ]);
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                if($reqData['status'] == 1){
                    $isSaved = SavedJobs::where('recruiter_job_id','=',$reqData['jobId'])->where('seeker_id','=',$userId)->count();
                    if($isSaved > 0){
                        $response = apiResponse::customJsonResponse(1, 201, trans("messages.job_already_saved"));
                    }else{
                        $saveJobs = array('recruiter_job_id' => $reqData['jobId'] , 'seeker_id' => $userId);
                        SavedJobs::insert($saveJobs);
                        $response = apiResponse::customJsonResponse(1, 200, trans("messages.save_job_success"));
                    }
                    
                }else{
                    SavedJobs::where('seeker_id', '=', $userId)->where('recruiter_job_id','=',$reqData['jobId'])->forceDelete();
                    $response = apiResponse::customJsonResponse(1, 200, trans("messages.unsave_job_success"));
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
    
    public function postApplyJob(Request $request){
        try{
            $this->validate($request, [
                'jobId' => 'required',
            ]);
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                $profileComplete = UserProfile::select('is_completed', 'is_job_seeker_verified')->where('user_id', $userId)->first();
                
                
                if($profileComplete->is_completed == 1){
                    
                    if($profileComplete->is_job_seeker_verified != UserProfile::JOBSEEKER_VERIFY_APPROVED) {
                        return apiResponse::customJsonResponse(0, 200, trans("messages.jobseeker_not_verified"));
                    }
                    
                    $jobExists = JobLists::where('seeker_id','=',$userId)
                                    ->where('recruiter_job_id','=',$reqData['jobId'])
                                    ->whereIn('applied_status',[JobLists::INVITED])
                                    ->first();
                    if($jobExists){
                        
                        //if($jobExists->applied_status == JobLists::INVITED){
                            JobLists::where('id', $jobExists->id)->update(['applied_status' => JobLists::APPLIED]);
                            $this->notifyAdmin($reqData['jobId'],$userId,Notification::JOBSEEKERAPPLIED);
                            $response = apiResponse::customJsonResponse(1, 200, trans("messages.apply_job_success"));
                        /*}else{
                           $response = apiResponse::customJsonResponse(0, 201, trans("messages.job_already_applied")); 
                        }*/
                    }else{
                        $applyJobs = array('seeker_id' => $userId , 'recruiter_job_id' => $reqData['jobId'] , 'applied_status' => JobLists::APPLIED);
                        JobLists::insert($applyJobs);
                        $this->notifyAdmin($reqData['jobId'],$userId,Notification::JOBSEEKERAPPLIED);
                        $response = apiResponse::customJsonResponse(1, 200, trans("messages.apply_job_success"));
                    }
                }else{
                    $response = apiResponse::customJsonResponse(0, 202, trans("messages.profile_not_complete"));
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
    
    public function postCancelJob(Request $request){
        try{
            $this->validate($request, [
                'jobId' => 'required',
                'cancelReason' => 'required',
            ]);
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                $jobExists = JobLists::select('id')->where('seeker_id','=',$userId)->where('recruiter_job_id','=',$reqData['jobId'])
                        ->whereIn('applied_status',[JobLists::SHORTLISTED,JobLists::APPLIED,  JobLists::HIRED])->first();
                if($jobExists){
                    $jobExists->applied_status = JobLists::CANCELLED;
                    $jobExists->cancel_reason = $reqData['cancelReason'];
                    $jobExists->save();
                    $this->notifyAdminForCancelJob($reqData['jobId'],$userId,$reqData['cancelReason']);
                    $response = apiResponse::customJsonResponse(1, 200, trans("messages.job_cancelled_success"));
                }else{
                    $response = apiResponse::customJsonResponse(0, 201, trans("messages.job_not_applied_by_you"));
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
    
    public function getJobList(Request $request){
        try{
            $this->validate($request, [
                'type' => 'required',
                'page' => 'required',
                'lat' => 'required',
                'lng' => 'required',
            ]);
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                $reqData['userId'] = $userId;
                $message = "";
                if($reqData['type'] == 1){
                    $searchResult = SavedJobs::listSavedJobs($reqData);
                    $message = trans("messages.saved_job_list");
                }else{
                    
                    $searchResult = JobLists::listJobsByStatus($reqData);
                    if($reqData['type'] == 2){
                        $message = trans("messages.applied_job_list");
                    }else{
                        $message = trans("messages.shortlisted_job_list");
                    }
                }
                if(count($searchResult['list']) > 0){
                    $response = apiResponse::customJsonResponse(1, 200, $message,  apiResponse::convertToCamelCase($searchResult));
                }else{
                    $response = apiResponse::customJsonResponse(0, 201, trans("messages.no_data_found"));
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

    public function postJobDetail(Request $request)
    {
        try{
            $this->validate($request, [
                'jobId' => 'required'
            ]);
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                $jobId = $reqData['jobId'];
                $data = RecruiterJobs::getJobDetail($jobId, $userId);
                if(!empty($data)) {
                    $data['is_applied'] = JobLists::isJobApplied($jobId,$userId);
                    $data['is_saved'] = SavedJobs::getJobSavedStatus($jobId, $userId);
                    $returnResponse = apiResponse::customJsonResponse(1, 200, trans('messages.job_detail_success'), apiResponse::convertToCamelCase($data));
                }else{
                    $returnResponse = apiResponse::customJsonResponse(0, 201, trans("messages.job_not_exists"));
                }
            }else{
                $returnResponse = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $returnResponse = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $returnResponse = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $returnResponse;
    }
    public function postAcceptRejectInvitedJob(Request $request){
        try{
            $this->validate($request, [
                'notificationId' => 'required',
                'acceptStatus' => 'required',
            ]);
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                $notificationDetails = Notification::where('id',$reqData['notificationId'])->first();
                $jobExists = JobLists::where('seeker_id','=',$userId)
                                        ->where('recruiter_job_id','=',$notificationDetails->job_list_id)
                                        ->orderBy('id','desc')
                                        ->first();
                if($jobExists){
                    if($jobExists->applied_status == JobLists::INVITED){
                        if($reqData['acceptStatus'] == 0){
                            $jobExists->applied_status = JobLists::CANCELLED;
                            $msg = trans("messages.job_cancelled_success");
                        }else{
                            $jobExists->applied_status = JobLists::HIRED;
                            $userChat = new ChatUserLists();
                            $userChat->recruiter_id = $notificationDetails->sender_id;
                            $userChat->seeker_id = $userId;
                            $userChat->checkAndSaveUserToChatList();
                            $msg = trans("messages.job_hired_success");
                        }
                        $jobExists->save();
                        Notification::where('id', $reqData['notificationId'])->update(['seen' => 1]);
                        if($reqData['acceptStatus'] == 0){
                            $this->notifyAdmin($notificationDetails->job_list_id,$userId,Notification::JOBSEEKERREJECTED);
                        }else{
                             $this->notifyAdmin($notificationDetails->job_list_id,$userId,Notification::JOBSEEKERACCEPTED);
                        }
                        $response = apiResponse::customJsonResponse(1, 200, $msg);
                    }else{
                        if($jobExists->applied_status == JobLists::HIRED){
                            $msg = "You have already hired for this job";
                        }else{
                            $msg = "You have already cancelled this job invitation";
                        }
                        $response = apiResponse::customJsonResponse(0, 201, $msg);
                    }
                }else{
                    $response = apiResponse::customJsonResponse(0, 201, trans("messages.not_invited_job"));
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            Log::error($e);
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            Log::error($e);
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
    
    public function notifyAdmin($jobId,$senderId,$notificationType){
        $receiverDetails = RecruiterJobs::join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                        ->join('job_titles','job_templates.job_title_id','=','job_titles.id')
                        ->select('job_templates.user_id','job_titles.jobtitle_name')
                        ->where('recruiter_jobs.id',$jobId)->first();
        $jobseekerDetails = UserProfile::getUserProfile($senderId);
        if($notificationType == Notification::JOBSEEKERAPPLIED){
            $message = '<a href="/job/details/'.$jobId.'" ><b>'.$jobseekerDetails['first_name'].' '.$jobseekerDetails['last_name'].'</a></b> has applied for '.$receiverDetails->jobtitle_name;
        }else if($notificationType == Notification::JOBSEEKERACCEPTED){
            $message = '<a href="/job/details/'.$jobId.'" ><b>'.$jobseekerDetails['first_name'].' '.$jobseekerDetails['last_name'].'</a></b> has accepted for '.$receiverDetails->jobtitle_name;
        }else if($notificationType == Notification::JOBSEEKERREJECTED){
            $message = '<a href="/job/details/'.$jobId.'" ><b>'.$jobseekerDetails['first_name'].' '.$jobseekerDetails['last_name'].'</a></b> has rejected for '.$receiverDetails->jobtitle_name;
        }
        $notificationDetails = ['image' => $jobseekerDetails['profile_pic'],'message' => $message];
        $data = ['receiver_id'=>$receiverDetails->user_id,'job_list_id' => $jobId,'sender_id' => $senderId, 'notification_data'=>json_encode($notificationDetails),'notification_type' => $notificationType];
        $notificationDetails = Notification::create($data);
    }
    
    public function notifyAdminForCancelJob($jobId,$senderId,$cancelReason){
        $receiverDetails = RecruiterJobs::join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                        ->join('job_titles','job_templates.job_title_id','=','job_titles.id')
                        ->select('job_templates.user_id','job_titles.jobtitle_name')
                        ->where('recruiter_jobs.id',$jobId)->first();
        $jobseekerDetails = UserProfile::getUserProfile($senderId);
        
            $message = '<a href="/job/details/'.$jobId.'" ><b>'.$jobseekerDetails['first_name'].' '.$jobseekerDetails['last_name'].'</a></b> has cancelled for '.$receiverDetails->jobtitle_name;
        
        $notificationDetails = ['image' => $jobseekerDetails['profile_pic'],'message' => $message,'cancel_reason' => $cancelReason];
        $data = ['receiver_id'=>$receiverDetails->user_id,'job_list_id' => $jobId,'sender_id' => $senderId, 'notification_data'=>json_encode($notificationDetails),'notification_type' => Notification::JOBSEEKERCANCELLED];
        $notificationDetails = Notification::create($data);
    }
    
    
}