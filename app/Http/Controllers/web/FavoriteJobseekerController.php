<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JobSeekerProfile;
use Auth;
use Validator;
use Exception;

class FavoriteJobseekerController extends Controller {

    public function getFavJobseeker(Request $request) {
        $favJobSeeker = JobSeekerProfile::join('favourites', 'jobseeker_profiles.user_id', '=', 'favourites.seeker_id')
                ->leftjoin('job_lists', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
                ->where('favourites.recruiter_id', Auth::user()->id)
                ->select('jobseeker_profiles.user_id as seeker_id', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'job_lists.applied_status')
                ->simplePaginate(15);
        return view('web.fav_jobseeker', ['favJobSeeker' => $favJobSeeker]);
    }

    public function postInviteJobseeker(Request $request) {
        $validator = Validator::make($request->all(), [
                    'selectJobSeeker' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('fav_jobseeker')
                            ->withErrors($validator)
                            ->withInput();
        }
        try {
            \App\Models\JobLists::create([
                'recruiter_job_id' => 1,
                'temp_job_id'=> 1,
                'seeker_id' =>12,
                'applied_status' => '1',
                'cancel_reason'=>'no reason'
            ]);
            return redirect('favorite-jobseeker');
        } catch (Exception $ex) {
            print_r($ex->getMessage());
        }
    }

}
