<?php

namespace App\Enums;

abstract class PartTime  {

    static function days(){
        return [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday'
        ];
    }
}