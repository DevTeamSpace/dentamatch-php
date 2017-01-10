<?php
namespace App\Http\Controllers\Cms;
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

class UserController extends Controller {
    
    public function __construct() {
        $this->middleware('cms')->except([]);
    }
    
    public function index(){
        dd('after login');
    }
    
    
}