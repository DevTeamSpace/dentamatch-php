<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SearchFilter extends Model
{
    use SoftDeletes;
  
    protected $table  = 'search_filters';
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
    
    public static function createFilter($userId, $data)
    {
        $searchFilterModel = static::where('user_id', $userId)->first();
        if(!$searchFilterModel)
        {
            $searchFilterModel = new SearchFilter();
        }
        $searchFilterModel->search_filter = json_encode($data);
        $searchFilterModel->user_id = $userId;
        $searchFilterModel->save();
    }
    
    public static function getFiltersOnLogin($userId)
    {
        $return = null;
        $searchFilterModel = static::where('user_id', $userId)->first();
        if($searchFilterModel)
        {
             $return = array();
             $return = json_decode($searchFilterModel->search_filter);
        }
        return $return;
    }
    

}
