<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use DB;
use App\Models\JobTitles;
use App\Helpers\apiResponse;
use App\Models\Skills;

class MasterApiController extends Controller {
    
    public function __construct() {
        
    }
    
    /**
     * Description : Get skills list
     * Method : getSkilllists
     * formMethod : GET
     * @param Request $request
     * @return type
     */
    public function getSkilllists(){
        $skill_lists = Skills::whereNull('parent_id')->with('children')->get();
        $response = apiResponse::customJsonResponseObject(1, 200, "Skill list",'skillList',  apiResponse::convertToCamelCase($skill_lists));
        return $response;
    }
    
}