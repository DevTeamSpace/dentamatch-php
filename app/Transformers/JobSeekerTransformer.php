<?php
namespace App\Transformers;

use App\Enums\SeekerVerifiedStatus;
use App\Models\JobSeekerProfiles;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class JobSeekerTransformer
{
    public function transformAll(Collection $items, $fields = null) {
        return $items->map([$this, 'transformItem'])->map(function ($item) use ($fields) {
            return Arr::flatten($fields? Arr::only($item, $fields) : $item);})->toArray();
    }

    public function transformItem(JobSeekerProfiles $item) {
        $result = [];
        $result['first_name'] = $item->first_name;
        $result['last_name'] = $item->last_name;
        $result['email'] = $item->user->email;
        $result['phone'] = '';
        $result['license_number'] = $item->license_number;
        $result['license_state'] = $item->state;
        $result['job_title'] = object_get($item, 'jobTitle.jobtitle_name');
        $result['skills'] = $item->user->skills->implode('skill_name', ',');
        $result['preferred_location'] = object_get($item, 'preferredLocation.preferred_location_name');
        $result['school_name'] = $item->user->schooling->pluck('pivot.other_schooling')->filter()->implode(',');
        $result['registration_date'] = $item->user->created_at->toDateString();
        $result['verification_status'] = SeekerVerifiedStatus::toString($item->is_job_seeker_verified);

        return $result;
    }

}
