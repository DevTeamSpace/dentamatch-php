<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    
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
