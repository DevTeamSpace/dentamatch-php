<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
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
        $response = ApiResponse::customJsonResponseObject(1, 200, "Preferred Job Location list",'skillList',  ApiResponse::convertToCamelCase($skill_lists));
        return $response;
    }
    
    /**
     * Description : Get Preferred Job Location list
     * Method : getPreferrefJobLocation
     * formMethod : GET
     * @param Request $request
     * @return type
     */
    public function getPreferrefJobLocation() {
        $preferreJobLocationModel = PreferredJobLocation::getAllPreferrefJobLocation();
        $response = ApiResponse::customJsonResponseObject(1, 200, "Skill list",'preferredJobLocations',  ApiResponse::convertToCamelCase($preferreJobLocationModel));
        return $response;
    }
    
}