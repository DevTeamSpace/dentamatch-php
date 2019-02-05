<?php

namespace App\Enums;

abstract class JobType  {

    const FULLTIME = 1;
    const PARTTIME = 2;
    const TEMPORARY = 3;

    static function ToString($type){
        switch($type){
            case self::FULLTIME: return 'Full Time';
            case self::PARTTIME: return 'Part Time';
            case self::TEMPORARY: return 'Temp';
        }
        return '';
    }
}