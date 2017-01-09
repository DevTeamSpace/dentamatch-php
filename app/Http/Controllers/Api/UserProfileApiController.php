<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use DB;
use Hash;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Device;
use App\Models\UserProfile;
use App\Models\PasswordReset;
use App\Models\JobTitles;
use Mail;
use Auth;
use App\Helpers\apiResponse;
use App\Services\UploadsManager;
class UserProfileApiController extends Controller {
    
    public function __construct() {
        $this->middleware('ApiAuth')->except([]);
    }
    public function uploadProfileImage(){
        
    }
    
    
}