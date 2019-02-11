<?php

namespace App\Enums;

abstract class SignupSource  {

    const APP = 1;
    const WEB = 2;

    static function ToString($type){
        switch($type){
            case self::APP: return 'App';
            case self::WEB: return 'Web';
        }
        return '';
    }
}