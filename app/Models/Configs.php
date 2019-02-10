<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Configs
 *
 * @property int $id
 * @property string $config_name
 * @property string $config_desc
 * @property string $config_data
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static Builder|Configs newModelQuery()
 * @method static Builder|Configs newQuery()
 * @method static Builder|Configs query()
 * @method static Builder|Configs whereConfigData($value)
 * @method static Builder|Configs whereConfigDesc($value)
 * @method static Builder|Configs whereConfigName($value)
 * @method static Builder|Configs whereCreatedAt($value)
 * @method static Builder|Configs whereId($value)
 * @method static Builder|Configs whereUpdatedAt($value)
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
