<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\apiResponse;
use App\Models\RecruiterJobs;
use App\Models\Location;
use App\Models\SavedJobs;

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
        try{
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
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
    
    
}