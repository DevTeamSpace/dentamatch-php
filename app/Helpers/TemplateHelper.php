<?php

namespace App\Helpers;

class TemplateHelper
{

    public static function getRatingClassName($ratingValue)
    {
        $rating = round($ratingValue);
        if ($rating > 3) return 'bg-green';
        if ($rating == 3) return 'bg-ember';
        return 'bg-red';
    }

    public static function getPartTimeString($data)
    {
        $days = [];

        if (array_get($data, 'is_monday') || array_get($data, 'is_parttime_monday'))
            $days[] = 'Monday';

        if (array_get($data, 'is_tuesday') || array_get($data, 'is_parttime_tuesday'))
            $days[] = 'Tuesday';

        if (array_get($data, 'is_wednesday') || array_get($data, 'is_parttime_wednesday'))
            $days[] = 'Wednesday';

        if (array_get($data, 'is_thursday') || array_get($data, 'is_parttime_thursday'))
            $days[] = 'Thursday';

        if (array_get($data, 'is_friday') || array_get($data, 'is_parttime_friday'))
            $days[] = 'Friday';

        if (array_get($data, 'is_saturday') || array_get($data, 'is_parttime_saturday'))
            $days[] = 'Saturday';

        if (array_get($data, 'is_sunday') || array_get($data, 'is_parttime_sunday'))
            $days[] = 'Sunday';

        return implode(', ', $days);
    }

    public static function getFirstDate($datesString)
    {
        $datesString = str_replace('|', ',', $datesString);
        $dates = explode(',', $datesString);
        return date('l, M d, Y', strtotime($dates[0]));
    }

    public static function isSeekerWorksPartTime($seekerDetails)
    {
        return $seekerDetails['is_parttime_monday'] || $seekerDetails['is_parttime_tuesday']
            || $seekerDetails['is_parttime_wednesday'] || $seekerDetails['is_parttime_thursday']
            || $seekerDetails['is_parttime_friday'] || $seekerDetails['is_parttime_saturday']
            || $seekerDetails['is_parttime_sunday'];
    }
}

