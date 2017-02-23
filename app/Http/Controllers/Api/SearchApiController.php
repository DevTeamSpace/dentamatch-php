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

class SearchApiController extends Controller {
    
    public function __construct() {
        
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
            $userId = apiResponse::loginUserId($request->header('accessToken'));
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
                    $response = apiResponse::customJsonResponse(0, 201, trans("messages.no_data_found"));
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
            $userId = apiResponse::loginUserId($request->header('accessToken'));
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
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $reqData = $request->all();
                $profileComplete = UserProfile::select('is_completed')->where('user_id', $userId)->first();
                if($profileComplete->is_completed == 1){
                    $jobExists = JobLists::where('seeker_id','=',$userId)
                                    ->where('recruiter_job_id','=',$reqData['jobId'])
                                    ->whereIn('applied_status',[JobLists::APPLIED,JobLists::INVITED])
                                    ->first();
                    if($jobExists){
                        if($jobExists->applied_status == JobLists::INVITED){
                            JobLists::where('id', $jobExists->id)->update(['applied_status' => JobLists::APPLIED]);
                            $response = apiResponse::customJsonResponse(1, 200, trans("messages.apply_job_success"));
                        }else{
                           $response = apiResponse::customJsonResponse(0, 201, trans("messages.job_already_applied")); 
                        }
                    }else{
                        $applyJobs = array('seeker_id' => $userId , 'recruiter_job_id' => $reqData['jobId'] , 'applied_status' => JobLists::APPLIED);
                        JobLists::insert($applyJobs);
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
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $reqData = $request->all();
                $jobExists = JobLists::select('id')->where('seeker_id','=',$userId)->where('recruiter_job_id','=',$reqData['jobId'])
                        ->whereIn('applied_status',[JobLists::SHORTLISTED,JobLists::APPLIED,  JobLists::HIRED])->first();
                if($jobExists){
                    $jobExists->applied_status = JobLists::CANCELLED;
                    $jobExists->cancel_reason = $reqData['cancelReason'];
                    $jobExists->save();
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
            $userId = apiResponse::loginUserId($request->header('accessToken'));
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
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $reqData = $request->all();
                $jobId = $reqData['jobId'];
                $data = RecruiterJobs::getJobDetail($jobId, $userId);
                if(!empty($data)) {
                    $data['is_applied'] = JobLists::isJobApplied($jobId,$userId);
                    $data['is_saved'] = SavedJobs::getJobSavedStatus($jobId, $userId);
                }
                
                $returnResponse = apiResponse::customJsonResponse(1, 200, trans('messages.job_detail_success'), apiResponse::convertToCamelCase($data));
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
    
    
}