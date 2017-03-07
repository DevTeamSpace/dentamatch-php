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
    
    public static function getJobSeekerAffiliation($userId)
    {   
         $query = static::select('affiliation_id as affiliationId', 'affiliations.affiliation_name as affiliationName', 'other_affiliation as otherAffiliation')
                            ->join('affiliations', 'affiliations.id', '=', 'jobseeker_affiliations.affiliation_id')
                            ->where('user_id',$userId)
                            ->where('affiliations.is_active',1)
                            ->orderBy('affiliation_id');
        
        $list = $query->get()->toArray();

        return $list;
    }
    
}
