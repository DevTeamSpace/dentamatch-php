<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreferredJobLocation extends Model {

    protected $table = 'preferred_job_locations';
    protected $primaryKey = 'id';
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'preferred_location_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public static function getAllPreferrefJobLocation() {
        return static::where('is_active',1)->get()->toArray();
    }
}
