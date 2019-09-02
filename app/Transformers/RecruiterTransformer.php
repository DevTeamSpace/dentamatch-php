<?php
namespace App\Transformers;

use App\Models\RecruiterProfile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class RecruiterTransformer
{
    public function transformAll(Collection $items, $fields = null) {
        return $items->map([$this, 'transformItem'])->map(function ($item) use ($fields) {
            return Arr::flatten($fields? Arr::only($item, $fields) : $item);})->toArray();
    }

    public function transformItem(RecruiterProfile $item) {
        $result = [];

        $result['email'] = $item->recruiter->email;
        $result['phone'] = '';
        $result['office_name'] = $item->office_name;
        $result['registration_date'] = $item->recruiter->created_at->toDateString();

        return $result;
    }

}
