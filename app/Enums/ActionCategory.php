<?php

namespace App\Enums;

abstract class ActionCategory  {

    const Recruiter = 1;
    const Seeker = 2;

    static function ToString($type){
        switch($type){
            case self::Recruiter: return 'Recruiter';
            case self::Seeker: return 'Talent';
        }
        return '';
    }
}
