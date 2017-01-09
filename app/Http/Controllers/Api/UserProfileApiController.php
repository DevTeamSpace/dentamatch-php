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
use App\Repositories\File\FileRepositoryS3;

class UserProfileApiController extends Controller {

    use FileRepositoryS3;

    public function __construct() {
        $this->middleware('ApiAuth')->except([]);
    }

    public function postChangePassword(Request $request) {
        try {
            $this->validate($request, [
                'oldPassword' => 'required|max:255',
                'newPassword' => 'required|min:6|max:255',
                'confirmNewPassword' => 'required|min:6|max:255'
            ]);
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }
        try {
            $reqData = $request->all();
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            dd($userId);
            $userModel = User::where('id', $userId)->first();
            if (!Hash::check($reqData['oldPassword'], $userModel->password)) {
                $response = apiResponse::customJsonResponse(0, 201, "Incorrect old password");
            } else if ($reqData['newPassword'] !== $reqData['confirmNewPassword']) {
                $response = apiResponse::customJsonResponse(0, 202, "Mismatch password and confirm password");
            } else if (!empty($userModel)) {
                $userModel->password = bcrypt($reqData['newPassword']);
                $userModel->save();
                $response = apiResponse::customJsonResponse(1, 200, "Password updated successfully");
            } else {
                $response = apiResponse::customJsonResponse(0, 204, "Token is invalid");
            }
            return $response;
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            return apiResponse::responseError("Some error occoured", ["data" => $message]);
        }
    }

    public function uploadImage(Request $request) {
        try {
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            $filename = $this->generateFilename($userId, $request->type);
            $response = $this->uploadFileToAWS($request, $filename);
            if ($response['res']) {
                $file = str_replace($request->type . '/', '', $response['file']);
                if ($request->type == 'profile_pic') {
                    UserProfile::where('user_id', $userId)->update(['profile_pic' => $file]);
                } else {
                    UserProfile::where('user_id', $userId)->update(['dental_state_board' => $file]);
                }
                $url['img_url'] = env('AWS_URL') . '/' . env('AWS_BUCKET') . '/' . $response['file'];
                return apiResponse::customJsonResponse(1, 200, "Image Saved successfully", $url);
            } else {
                return apiResponse::responseError("Problem in uploading image.");
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }
    }

    public function putUpdateLicense(Request $request) {
        try {
            $this->validate($request, [
                'license' => 'required',
                'state' => 'required'
            ]);
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }
        try {
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            UserProfile::where('user_id', $userId)->update(['license_number' => $request->license, 'state' => $request->state]);
            return apiResponse::customJsonResponse(1, 200, "data Saved successfully");
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }
    }

}
