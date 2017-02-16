<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model {
    
    const ADMIN = 1;
    const RECRUITER = 2;
    const JOBSEEKER = 3;

    protected $table = 'user_groups';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'group_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
