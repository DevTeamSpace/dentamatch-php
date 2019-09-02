<?php

namespace App\Enums;

abstract class SeekerVerifiedStatus  {

    const NOT_VERIFIED = 0;
    const APPROVED = 1;
    const REJECT = 2;

    static function toString($code) {
        switch ($code) {
            case self::NOT_VERIFIED: return 'Not verified';
            case self::APPROVED: return 'Approved';
            case self::REJECT: return 'Rejected';
        }

        return null;
    }
}
