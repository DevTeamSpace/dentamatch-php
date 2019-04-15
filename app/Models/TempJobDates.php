<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Models\TempJobDates
 *
 * @property int $id
 * @property int $recruiter_job_id
 * @property string $job_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at            SOFT DELETE WAS REMOVED DUE TO LACK OF USING IT IN JOINs
 * @property-read string|null $mapping_for
 * @property-read RecruiterJobs $job
 *
 * @method static Builder|TempJobDates newModelQuery()
 * @method static Builder|TempJobDates newQuery()
 * @method static Builder|TempJobDates query()
 * @method static Builder|TempJobDates whereCreatedAt($value)
 * @method static Builder|TempJobDates whereDeletedAt($value)
 * @method static Builder|TempJobDates whereId($value)
 * @method static Builder|TempJobDates whereJobDate($value)
 * @method static Builder|TempJobDates whereRecruiterJobId($value)
 * @method static Builder|TempJobDates whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TempJobDates extends Model
{
    use Eloquence, Mappable;

    protected $table = 'temp_job_dates';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    protected $maps = [
        'recruiterJobId' => 'recruiter_job_id',
        'jobDate'        => 'job_date',
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['recruiterJobId', 'jobDate'];
    protected $appends = ['recruiterJobId', 'jobDate'];

    public function job()
    {
        return $this->belongsTo(RecruiterJobs::class, 'recruiter_job_id');
    }
}
