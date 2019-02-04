<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\AppMessage
 *
 * @property int $id
 * @property int $message_to 1=>All,2=>Recruiter,3=>Job Seeker
 * @property string $message
 * @property int|null $message_sent
 * @property int|null $cron_message_sent
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string|null $mapping_for
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AppMessage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage whereCronMessageSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage whereMessageSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage whereMessageTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AppMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AppMessage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AppMessage withoutTrashed()
 * @mixin \Eloquent
 */
class AppMessage extends Model
{
    use Eloquence, Mappable;
    use SoftDeletes;
    protected $table        = 'app_messages';
    protected $primaryKey   = 'id';
   
    protected $maps          = [
        'appMessageId' => 'id',
        'messageTo'=>'message_to',
        'messageSent'=>'message_sent',
        'cronMessageSent' => 'cron_message_sent',
        'createdAt'=>'created_at',
        ];
    protected $hidden       = ['message_to','message_sent', 'cron_message_sent','created_at','updated_at'];
    protected $fillable     = [];
    protected $appends      = ['messageTo','messageSent', 'cronMessageSent','createdAt'];
    protected $dates = ['deleted_at'];
    
    protected function updateData($data){
        $appMessage = $this->findById($data->id); 
        
        $return = false;
        if($appMessage->save()) {
            $return = true;
        }
        
        return $return;
    }
    
    protected function findById($id){
        return static::find($id);
    }
}
