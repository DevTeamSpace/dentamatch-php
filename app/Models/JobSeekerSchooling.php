<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobSeekerSchooling extends Model
{
    use SoftDeletes;
  
    protected $table  = 'jobseeker_schoolings';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
       'updated_at', 'deleted_at'
    ];
    
    public static function getUserSchoolingList($userId)
    {   
        $query = static::where('user_id',$userId)->orderBy('id');
        $list = $query->get()->toArray();

        return $list;
    }
    

}
