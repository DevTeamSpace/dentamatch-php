<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JobSeekerProfile;
use Auth;
use Validator;
use Exception;
use DB;
use App\Models\JobTemplates;
use App\Models\Favourite;

class FavoriteJobseekerController extends Controller {

    public function getFavJobseeker(Request $request) {
        $userId = Auth::user()->id;
        $rating = "(avg(job_ratings.punctuality) + avg(job_ratings.time_management) + avg(job_ratings.skills) + avg(job_ratings.teamwork) + avg(job_ratings.onemore))/5";
        
        $favJobSeeker = Favourite::join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'favourites.seeker_id')
                ->leftjoin('job_lists', 'favourites.seeker_id', '=', 'job_lists.seeker_id')
                ->leftjoin('job_ratings', 'favourites.seeker_id', '=', 'job_ratings.seeker_id')
                ->where('favourites.recruiter_id', Auth::user()->id)
                ->select(DB::raw('(avg(job_ratings.punctuality) + avg(job_ratings.time_management) + avg(job_ratings.skills) + avg(job_ratings.teamwork) + avg(job_ratings.onemore))/5 as sum'), 'jobseeker_profiles.user_id as seeker_id', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'job_lists.applied_status')
                ->groupby('favourites.seeker_id')
                ->simplePaginate(15);
        
        
        $jobDetail = JobTemplates::join('recruiter_jobs', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_titles', 'job_templates.job_title_id', '=', 'job_titles.id')
                ->where('recruiter_jobs.job_type', '3')
                ->select('job_titles.jobtitle_name', 'recruiter_jobs.id as recruiterId')
                ->get();
        
        $jobTemplateModalData = JobTemplates::getAllUserTemplates($userId);
        return view('web.fav_jobseeker', ['favJobSeeker' => $favJobSeeker, 'jobDetail' => $jobDetail, 'jobTemplateModalData' => $jobTemplateModalData]);
    }

    public function postInviteJobseeker(Request $request) {
        $validator = Validator::make($request->all(), [
                    'selectJobSeeker' => 'required',
                    'seekerId' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('fav_jobseeker')
                            ->withErrors($validator)
                            ->withInput();
        }
        try {
            $jobList = \App\Models\JobLists::where('seeker_id', $request->seekerId)->first();
            if (isset($jobList) && !empty($jobList)) {
                if ($jobList->recruiter_job_id != $request->selectJobSeeker) {
                    \App\Models\JobLists::create([
                        'recruiter_job_id' => $request->selectJobSeeker,
                        'seeker_id' => $request->seekerId,
                        'applied_status' => '1',
                    ]);
                }
            } else {
                \App\Models\JobLists::create([
                    'recruiter_job_id' => $request->selectJobSeeker,
                    'seeker_id' => $request->seekerId,
                    'applied_status' => '1',
                ]);
            }
            return redirect('favorite-jobseeker');
        } catch (Exception $ex) {
            print_r($ex->getMessage());
        }
    }
    
    /**
     * Mark/UnMark as Favourite
     * @param type $seekerId
     * @return int
     */
    public function getMarkFavourite($seekerId)
    {
        $return=[];
        $recruiterId = Auth::user()->id;
        $favModel = Favourite::where('recruiter_id', $recruiterId)->where('seeker_id', $seekerId)->first();
        if($favModel) {
            $return['isFavourite'] = "No";
            $return['seekerId'] = $seekerId;
            $return['success'] = 1;
            $favModel->forceDelete();
        } else {
            $favModel = new Favourite();
            $favModel->recruiter_id = $recruiterId;
            $favModel->seeker_id = $seekerId;
            $favModel->save();
            $return['isFavourite'] = "Yes";
            $return['seekerId'] = $seekerId;
            $return['success'] = 1;   
        }
        
        return $return;
    }

}
