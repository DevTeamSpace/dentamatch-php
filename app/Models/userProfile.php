<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model {

    protected $table = 'jobseeker_profiles';
    protected $primaryKey = 'id';
    

    public function user() {
        return $this->belongsTo(User::class);
    }

}
