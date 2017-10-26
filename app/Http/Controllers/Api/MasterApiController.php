<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\apiResponse;
use App\Models\Skills;
use App\Models\PreferredJobLocation;

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
        $response = apiResponse::customJsonResponseObject(1, 200, "Preferred Job Location list",'skillList',  apiResponse::convertToCamelCase($skill_lists));
        return $response;
    }
    
    public function getPreferrefJobLocation() {
        $preferreJobLocationModel = PreferredJobLocation::getAllPreferrefJobLocation();
        $response = apiResponse::customJsonResponseObject(1, 200, "Skill list",'preferredJobLocations',  apiResponse::convertToCamelCase($preferreJobLocationModel));
        return $response;
    }
    
}