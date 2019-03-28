<?php

namespace App\Helpers;

use App\Enums\JobType;
use App\Models\JobSeekerProfiles;
use App\Models\RecruiterJobs;

class JobsHelper
{
    public static function seekerFitsJob(JobSeekerProfiles $jobSeeker, RecruiterJobs $jobDetails)
    {
        return
            ($jobDetails->job_type == JobType::FULLTIME && $jobSeeker->is_fulltime == 1) ||
            ($jobDetails->job_type == JobType::PARTTIME &&
                (($jobDetails->is_monday == 1 && $jobSeeker->is_parttime_monday == 1) ||
                    ($jobDetails->is_tuesday == 1 && $jobSeeker->is_parttime_tuesday == 1) ||
                    ($jobDetails->is_wednesday == 1 && $jobSeeker->is_parttime_wednesday == 1) ||
                    ($jobDetails->is_thursday == 1 && $jobSeeker->is_parttime_thursday == 1) ||
                    ($jobDetails->is_friday == 1 && $jobSeeker->is_parttime_friday == 1) ||
                    ($jobDetails->is_saturday == 1 && $jobSeeker->is_parttime_saturday == 1) ||
                    ($jobDetails->is_sunday == 1 && $jobSeeker->is_parttime_sunday == 1)));
    }

}

