<?php

namespace App\Enums;

abstract class JobAppliedStatus  {

    const INVITED = 1;
    const APPLIED = 2;
    const SHORTLISTED = 3;
    const HIRED = 4;
    const REJECTED = 5;
    const CANCELLED = 6;

    static function ToString($type){
        switch($type){
            case self::INVITED: return 'Invited';
            case self::APPLIED: return 'Applied';
            case self::SHORTLISTED: return 'Shortlisted';
            case self::HIRED: return 'Hired';
            case self::REJECTED: return 'Rejected';
            case self::CANCELLED: return 'Cancelled';
        }
        return '';
    }
}