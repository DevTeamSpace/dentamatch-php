<?php

namespace App\Enums;

abstract class ActionType  {

    const UserSignUp = 1;
    const UserLogin = 2;
    const EmailVerification = 3;
    const ApiSearch = 4;
    const SeekerApplied = 5;
    const SeekerCancelled = 6;
    const SeekerProfileUpdated = 7;

    const RecruiterPostJob = 50;
    const RecruiterInvite = 51;
    const RecruiterCancel = 52;
    const RecruiterHire = 53;
    const RecruiterCheckPromoCode = 54;

    static function ToString($type){
        switch($type){
            case self::UserSignUp: return 'User Signup';
            case self::UserLogin: return 'User Login';
            case self::EmailVerification: return 'Email Verification';
            case self::ApiSearch: return 'Job Search';
            case self::SeekerApplied: return 'Talent applied';
            case self::SeekerCancelled: return 'Talent cancelled';
            case self::SeekerProfileUpdated: return 'Talent profile updated';

            case self::RecruiterPostJob: return 'Job posted';
            case self::RecruiterInvite: return 'Invite sent';
            case self::RecruiterCancel: return 'Application cancelled';
            case self::RecruiterHire: return 'Hired';
            case self::RecruiterCheckPromoCode: return 'Promo Code tried';
        }
        return '';
    }
}
