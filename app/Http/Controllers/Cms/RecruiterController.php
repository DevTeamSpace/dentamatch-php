<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\WebResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\RecruiterProfile;
use Facades\App\Transformers\RecruiterTransformer;

class RecruiterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cms');
    }

    /**
     * Show the form to create a new recruiter.
     *
     * @return Response
     */
    public function create()
    {
        return view('cms.recruiter.create');
    }

    /**
     * List all recruiter.
     *
     * @return Response
     */
    public function index()
    {
        return view('cms.recruiter.index');
    }

    /**
     * Show the form to update an existing recruiter.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->select(['users.email', 'users.id', 'users.is_active'])
            ->where('users.id', $id)->first();
        return view('cms.recruiter.update', ['userProfile' => $user]);
    }

    /**
     * Store a new/update recruiter.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($request->id)],
        ];

        $this->validate($request, $rules);

        if (isset($request->id)) {
            $activationStatus = isset($request->is_active) ? 1 : 0;
            User::where('id', $request->id)->update(['email' => $request->email, 'is_active' => $activationStatus]);
            $msg = trans('messages.recruiter_updated_success');
        } else {
            $user = ['email' => $request->email, 'password' => '', 'is_verified' => 1, 'is_active' => 1];

            $userId = User::insertGetId($user);
            $userGroupModel = new UserGroup();
            $userGroupModel->group_id = UserGroup::RECRUITER;
            $userGroupModel->user_id = $userId;
            $userGroupModel->save();

            RecruiterProfile::create(['user_id' => $userId]);

            Password::broker()->sendResetLink(['email' => $request->email]);

            $msg = trans('messages.recruiter_added_success');
        }

        Session::flash('message', $msg);
        return redirect('cms/recruiter/index');
    }

    /**
     * Delete a recruiter.
     *
     * @param  int $id
     * @return JsonResponse
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return WebResponse::successResponse(trans('messages.record_was_deleted'));
    }

    /**
     * Method to get list of recruiter
     * @return Response
     */
    public function recruiterList()
    {
        $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->leftjoin('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'users.id')
            ->select(
                'recruiter_profiles.office_name',
                'users.email', 'users.id',
                'users.is_active'
            )
            ->where('user_groups.group_id', UserGroup::RECRUITER)
            ->orderBy('users.id', 'desc');
        return Datatables::of($userData)->make(true);
    }

    /**
     * Method to view page for reset password
     * @param $id
     * @return Response
     */
    public function adminResetPassword($id)
    {
        $user = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->select(['users.email', 'users.id', 'users.is_active'])
            ->where('users.id', $id)->first();
        return view('cms.recruiter.admin-reset-password', ['userProfile' => $user]);
    }

    /**
     * Method to send email for reset password
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function storeAdminResetPassword(Request $request)
    {
        $rules = [
            'id'    => ['required'],
            'email' => ['required', 'email'],
        ];

        $this->validate($request, $rules);

        $userId = $request->id;
        $email = $request->email;
        User::where('id', $userId)->update(['password' => '']);

        Password::broker()->sendResetLink(['email' => $email]);

        Session::flash('message', trans('messages.admin_recruiter_password_success'));
        $user = UserGroup::where('user_id', $userId)->first();
        if ($user->group_id == UserGroup::RECRUITER) {
            return redirect('cms/recruiter/index');
        } else {
            return redirect('cms/jobseeker/index');
        }
    }

    /**
     * Method to view recruiter detail view
     * @param $id
     * @return Response
     */
    public function recruiterView($id)
    {
        $userProfile = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->leftjoin('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'users.id')
            ->select(
                'recruiter_profiles.office_name',
                'recruiter_profiles.office_desc',
                'users.email', 'users.id',
                'users.is_active'
            )
            ->where('users.id', $id)->first();

        return view('cms.recruiter.view', ['userProfile' => $userProfile]);
    }

    /**
     * GET /cms/recruiter/csvRecruiter
     */
    public function csvRecruiter(){
        $list = RecruiterProfile::query()
            ->with(['recruiter:id,email,is_verified,is_active,created_at', 'offices', 'offices.officeTypes', 'offices.officeTypes.officeTypes'])
            ->get(['id', 'user_id', 'office_name', 'office_desc', 'accept_term']);

        $fields = ['email', 'phone', 'office_name', 'office_description', 'accept_term', 'email_verified', 'is_active', 'registration_date'];
        for ($i=1; $i<4; $i++) {
            $office = "office_$i";
            $fields[] = "{$office}_type";
            $fields[] = "{$office}_address";
            $fields[] = "{$office}_address_second_line";
            $fields[] = "{$office}_phone";
            $fields[] = "{$office}_working_hours";
            $fields[] = "{$office}_office_info";
        }

        $data = RecruiterTransformer::transformAll($list, $fields);

        return WebResponse::csvResponse($data, $fields, 'recruiters');
    }

}
