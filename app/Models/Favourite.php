<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Favourite
 *
 * @property int $id
 * @property int $recruiter_id
 * @property int $seeker_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string|null $mapping_for
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favourite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favourite newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Favourite onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favourite query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favourite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favourite whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favourite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favourite whereRecruiterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favourite whereSeekerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favourite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Favourite withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Favourite withoutTrashed()
 * @mixin \Eloquent
 */
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
