<?php
namespace App\Transformers;

use App\Enums\ActionCategory;
use App\Enums\ActionType;
use App\Models\ActionLog;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ActivityTransformer
{
    public function transformAll(Collection $items, $fields = null) {
        return $items->map([$this, 'transformItem'])->map(function ($item) use ($fields) {
            return Arr::flatten($fields? Arr::only($item, $fields) : $item);})->toArray();
    }

    public function transformItem(ActionLog $item) {
        $result = [];

        $result['category'] = ActionCategory::ToString($item->category);
        $result['type'] = ActionType::ToString($item->type);
        $result['user'] = $item->user->email;
        $result['job_title'] = object_get($item, 'job.jobTemplate.jobTitle.jobtitle_name');
        $result['date'] = $item->created_at->toDateTimeString();

        return $result;
    }

}
