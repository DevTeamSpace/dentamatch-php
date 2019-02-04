<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\TempJobDates
 *
 * @property int $id
 * @property int $recruiter_job_id
 * @property string $job_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read string|null $mapping_for
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempJobDates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempJobDates newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TempJobDates onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempJobDates query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempJobDates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempJobDates whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempJobDates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempJobDates whereJobDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempJobDates whereRecruiterJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TempJobDates whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TempJobDates withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TempJobDates withoutTrashed()
 * @mixin \Eloquent
 */
class TempJobDates extends Model
{   
    use Eloquence, Mappable, SoftDeletes;
    
    protected $table = 'temp_job_dates';
    protected $primaryKey = 'id';
    
    protected $guarded = ['id'];
    protected $maps          = [
        'recruiterJobId' => 'recruiter_job_id',
        'jobDate' => 'job_date',
        ];
    protected $hidden       = ['created_at','updated_at'];
    protected $fillable     = ['recruiterJobId','jobDate'];
    protected $appends      = ['recruiterJobId','jobDate'];
}
