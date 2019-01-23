<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Affiliation extends Model
{
    use SoftDeletes;
  
    protected $table  = 'affiliations';
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
    
    
    public static function getAffiliationList()
    {
        $list = [];
        $affiliationModel = static::select('affiliations.id as affiliationId', 'affiliation_name as affiliationName', 
                                        'affiliations.is_active as isActive', 'affiliations.created_at as createdAt')
                                ->where('affiliations.is_active',1)->orderBy('affiliations.id')->get();
        
        if($affiliationModel) {
            $list = $affiliationModel->toArray();
        }
        
        return $list;
    }
    
    

}
