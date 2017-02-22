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
    
    public static function getSearchRadius()
    {
        $return = 0;
        $configModel = static::where('config_name', 'SEARCHRADIUS')->first();
        if($configModel) {
            $return = $configModel->config_data;
        }
        return $return;
        
    }
    
    
}
