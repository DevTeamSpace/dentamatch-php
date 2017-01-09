<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use DB;
use App\Models\JobTitles;
use App\Helpers\apiResponse;

class MasterApiController extends Controller {
    
    public function __construct() {
        
    }
    public function getSkilllists(){
        $job_title = JobTitles::where('is_active',1)->get()->toArray();
        $response = apiResponse::customJsonResponseObject(1, 200, "Skill list",'skillList',$job_title);
        return $response;
    }
    
}