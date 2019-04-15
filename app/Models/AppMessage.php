<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\AppMessage
 *
 * @property int $id
 * @property int
 * @property int $message_to 1=>All,2=>Recruiter,3=>Job Seeker
 * @property string $message
 * @property int|null $message_sent
 * @property int|null $cron_message_sent
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string|null $mapping_for
 * @method static bool|null forceDelete()
 * @method static Builder|AppMessage newModelQuery()
 * @method static Builder|AppMessage newQuery()
 * @method static QueryBuilder|AppMessage onlyTrashed()
 * @method static Builder|AppMessage query()
 * @method static bool|null restore()
 * @method static Builder|AppMessage toBeSent()
 * @method static Builder|AppMessage whereCreatedAt($value)
 * @method static Builder|AppMessage whereCronMessageSent($value)
 * @method static Builder|AppMessage whereDeletedAt($value)
 * @method static Builder|AppMessage whereId($value)
 * @method static Builder|AppMessage whereMessage($value)
 * @method static Builder|AppMessage whereMessageSent($value)
 * @method static Builder|AppMessage whereMessageTo($value)
 * @method static Builder|AppMessage whereUpdatedAt($value)
 * @method static QueryBuilder|AppMessage withTrashed()
 * @method static QueryBuilder|AppMessage withoutTrashed()
 * @mixin \Eloquent
 */
class AppMessage extends Model
{
    use Eloquence, Mappable;
    use SoftDeletes;
    protected $table = 'app_messages';
    protected $primaryKey = 'id';

    protected $maps = [
        'appMessageId'    => 'id',
        'messageTo'       => 'message_to',
        'messageSent'     => 'message_sent',
        'cronMessageSent' => 'cron_message_sent',
        'createdAt'       => 'created_at',
    ];
    protected $hidden = ['message_to', 'message_sent', 'cron_message_sent', 'created_at', 'updated_at'];
    protected $fillable = [];
    protected $appends = ['messageTo', 'messageSent', 'cronMessageSent', 'createdAt'];
    protected $dates = ['deleted_at'];

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeToBeSent($query)
    {
        return $query->where('cron_message_sent', 0)->where('message_sent', 1);
    }
}
