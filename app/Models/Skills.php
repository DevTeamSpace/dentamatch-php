<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    //
    protected $table = 'skills';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'skillName' => 'skill_name',
        ];
    protected $hidden       = ['is_active','created_at','updated_at'];
    
   
    public function parent()
    {
        return $this->belongsTo(Skills::class,'parent_id')->where('parent_id',null)->where('is_active',1);
    }

    public function children()
    {
        return $this->hasMany(Skills::class,'parent_id')->where('is_active',1)->where('parent_id','<>',null);
    }
}