<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Configs
 *
 * @property int $id
 * @property string $config_name
 * @property string $config_desc
 * @property string $config_data
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Configs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Configs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Configs query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Configs whereConfigData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Configs whereConfigDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Configs whereConfigName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Configs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Configs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Configs whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Configs extends Model
{
    const CERTIFICATE_EXPIRE_DAYS = 'constants.notification.certificate_expire';

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
