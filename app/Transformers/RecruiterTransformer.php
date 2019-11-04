<?php
namespace App\Transformers;

use App\Models\RecruiterOffice;
use App\Models\RecruiterProfile;
use Carbon\Carbon;
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
        $result['office_description'] = $item->office_desc;
        $result['accept_term'] = $item->accept_term;
        $result['email_verified'] = $item->recruiter->is_verified;
        $result['is_active'] = $item->recruiter->is_active;
        $result['registration_date'] = $item->recruiter->created_at->toDateString();

        $officeFields = ['type', 'address', 'address_second_line', 'phone', 'working_hours', 'office_info'];
        for ($i=1; $i<4; $i++) {
            $officeCode = "office_${i}_";
            foreach ($officeFields as $field) {
                $result[$officeCode . $field] = null;
            }
        }

        foreach ($item->offices as $ind => $office) {
            $officeCode = 'office_' . ($ind+1);
            $result["{$officeCode}_type"] = $office->officeTypes->implode('officeTypes.officetype_name', ', ');
            $result["{$officeCode}_address"] = $office->address;
            $result["{$officeCode}_address_second_line"] = $office->address_second_line;
            $result["{$officeCode}_phone"] = $office->phone_no;
            $result["{$officeCode}_working_hours"] = $this->transformWorkingHours($office);
            $result["{$officeCode}_office_info"] = $office->office_info;
        }

        return $result;
    }

    /**
     * @param $office RecruiterOffice
     * @return string
     */
    private function transformWorkingHours($office) {
        if (($every_start = $this->format($office->work_everyday_start)) && ($every_end = $this->format($office->work_everyday_end))) {
            return 'Everyday: ' . $every_start . ' - ' . $every_end;
        }
        $otherDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $otherDaysHours = [];

        foreach ($otherDays as $day) {
            if (($start = $this->format($office->{$day."_start"})) && ($end = $this->format($office->{$day."_end"}))) {
                $otherDaysHours[] = ucfirst($day) . ': ' . $start . ' - ' . $end;
            }
        }

        return implode(', ', $otherDaysHours);

    }

    private function format($time) {
        if ($time == null || $time == '00:00:00')
            return null;
        return Carbon::createFromTimeString($time)->format('g:ia');
    }

}
