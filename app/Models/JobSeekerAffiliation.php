<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobSeekerAffiliation extends Model
{
    use SoftDeletes;
  
    protected $table  = 'jobseeker_affiliations';
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
    
    public static function getUserAffiliationList($userId)
    {   
        $list = [];
        $query = static::select('affiliation_id as affiliationId', 'other_affiliation as otherAffiliation')
                    ->where('user_id',$userId)->orderBy('affiliation_id')->get();
        
        if($query) {
            $list = $query->toArray();
        }
        
        return $list;
    }
    
}
