<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favourite extends Model
{
    use Eloquence, Mappable;
    use SoftDeletes;
    protected $table        = 'favourites';
    protected $primaryKey   = 'id';
   
    protected $maps          = [
        'recruiterId'=>'message_to',
        'seekerId'=>'message_sent',
        'cronMessageSent' => 'cron_message_sent'
        ];
    protected $hidden       = ['id','recruiter_id','seeker_id', 'created_at','updated_at', 'deleted_at'];
    protected $fillable     = [];
    protected $appends      = ['recruiterId','seekerId'];
    protected $dates = ['deleted_at'];
    
}
