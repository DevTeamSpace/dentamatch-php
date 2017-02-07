<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RecruiterProfile extends Model {

    protected $table = 'recruiter_profiles';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'is_subscribed', 'accept_term', 'free_period', 'auto_renewal', 'validity', 'office_name', 'office_desc'];

    public static function updateOfficeDetail($request) {
        return RecruiterProfile::where('user_id', Auth::user()->id)->update([
                    'office_name' => $request->officeName,
                    'office_desc' => $request->officeDescription
        ]);
    }
    
    public static function updateStripeToken($token){
        return RecruiterProfile::where(['user_id' => Auth::user()->id])->update(['stripe_token' => $token]);
    }

}
