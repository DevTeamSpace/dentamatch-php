<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configs extends Model
{
    //
    protected $table = 'configs';
    protected $primaryKey = 'id';
    
    
    protected $fillable = ['config_name', 'config_desc'];
    
    
    protected $hidden       = ['created_at','updated_at'];
    
    
}
