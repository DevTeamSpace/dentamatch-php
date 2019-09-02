<?php
namespace App\Transformers;

use App\Enums\JobType;
use App\Models\RecruiterJobs;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class JobTransformer
{
    public function transformAll(Collection $items, $fields = null) {
        return $items->map([$this, 'transformItem'])->map(function ($item) use ($fields) {
            return Arr::flatten($fields? Arr::only($item, $fields) : $item);})->toArray();
    }

    public function transformItem(RecruiterJobs $item) {
        $result = [];

        $result['office_name'] = $item->office_name;
        $result['job_title'] = $item->jobtitle_name;
        $result['job_type'] = JobType::ToString($item->job_type);
        $result['pay_rate'] = $item->job_type === JobType::TEMPORARY? $item->pay_rate : null;
        $result['invited'] = $item->invited;
        $result['applied'] = $item->applied;
        $result['sortlisted'] = $item->sortlisted;
        $result['hired'] = $item->hired;
        $result['rejected'] = $item->rejected;
        $result['cancelled'] = $item->cancelled;
        $result['status'] = $item->job_type !== JobType::TEMPORARY || $item->future_temp_dates_count > 0? 'active' : 'expired';

        $result['published_on'] = $item->created_at->toDateString();

        return $result;
    }

}
