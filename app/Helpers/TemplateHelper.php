<?php
namespace App\Helpers;

class TemplateHelper {

    public static function getRatingClassName($ratingValue) {
        $rating = round($ratingValue);
        if ($rating > 3) return 'bg-green';
        if ($rating == 3) return 'bg-ember';
        return 'bg-red';
    }
}

