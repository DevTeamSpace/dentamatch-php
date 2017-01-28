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

class SearchApiController extends Controller {
    
    public function __construct() {
        
    }
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
                $location = Location::where('zipcode',$reqData['zipCode'])->get();
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
    
    public function postSaveUnsavejob(Request $request){
        //try{
            $this->validate($request, [
                'jobId' => 'required',
                'status' => 'required',
            ]);
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $reqData = $request->all();
                if($reqData['status'] == 1){
                    $saveJobs = array('recruiter_job_id' => $reqData['jobId'] , 'seeker_id' => $userId);
                    SavedJobs::insert($saveJobs);
                    $response = apiResponse::customJsonResponse(1, 200, trans("messages.save_job_success"));
                }else{
                    SavedJobs::where('seeker_id', '=', $userId)->where('recruiter_job_id','=',$reqData['jobId'])->forceDelete();
                    $response = apiResponse::customJsonResponse(1, 200, trans("messages.unsave_job_success"));
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        /*} catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }*/
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
                $profileComplete = UserProfile::select('is_completed')->where('user_id', $userId)->get()->first();
                if($profileComplete->is_completed == 1){
                    $jobExists = JobLists::where('seeker_id','=',$userId)
                                    ->where('recruiter_job_id','=',$reqData['jobId'])
                                    ->where('applied_status','=',JobLists::APPLIED)
                                    ->get();
                    if($jobExists->count() > 0){
                        $response = apiResponse::customJsonResponse(0, 201, trans("messages.job_already_applied"));
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
                $jobExists = JobLists::select('id')->where('seeker_id','=',$userId)->where('recruiter_job_id','=',$reqData['jobId'])->get()->first();
                if($jobExists->count() > 0){
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
                if($reqData['type'] == 1){
                    $searchResult = SavedJobs::listSavedJobs($reqData);
                }else{
                    $searchResult = JobLists::listJobsByStatus($reqData);
                }
                if(count($searchResult['list']) > 0){
                    $response = apiResponse::customJsonResponse(1, 200, trans("messages.job_search_list"),  apiResponse::convertToCamelCase($searchResult));
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
                $data['is_applied'] = JobLists::isJobApplied($jobId,$userId);
                $data['is_saved'] = SavedJobs::getJobSavedStatus($jobId, $userId);
                
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