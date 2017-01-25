<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schooling extends Model
{
    use SoftDeletes;
  
    protected $table  = 'schoolings';
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
    
    public static function getScoolingList()
    {
        $query = static::select('schoolings.id as parentId', 'schoolingsChild.id as childId', 'schoolings.school_name as schoolName', 'schoolingsChild.school_name as schoolChildName')
                ->leftjoin('schoolings AS schoolingsChild','schoolingsChild.parent_id','=','schoolings.id')
                ->where('schoolings.is_active', 1)
                ->whereNull('schoolings.parent_id')
                ->orderBy('schoolings.id')
                ->orderBy('schoolingsChild.id');
        
        $list = $query->get()->toArray();

        return $list;
    }
    

}
