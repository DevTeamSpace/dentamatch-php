<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\JobSeekerAffiliation
 *
 * @property int $id
 * @property int $user_id
 * @property int $affiliation_id
 * @property string|null $other_affiliation
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerAffiliation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerAffiliation newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobSeekerAffiliation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerAffiliation query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerAffiliation whereAffiliationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerAffiliation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerAffiliation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerAffiliation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerAffiliation whereOtherAffiliation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerAffiliation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerAffiliation whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobSeekerAffiliation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobSeekerAffiliation withoutTrashed()
 * @mixin \Eloquent
 */
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
