<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\JobRatings
 *
 * @property int $id
 * @property int $recruiter_job_id
 * @property int|null $temp_job_id
 * @property int $seeker_id
 * @property int $punctuality
 * @property int $time_management
 * @property int $skills
 * @property int $teamwork
 * @property int $onemore
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|JobRatings newModelQuery()
 * @method static Builder|JobRatings newQuery()
 * @method static Builder|JobRatings query()
 * @method static Builder|JobRatings whereCreatedAt($value)
 * @method static Builder|JobRatings whereId($value)
 * @method static Builder|JobRatings whereOnemore($value)
 * @method static Builder|JobRatings wherePunctuality($value)
 * @method static Builder|JobRatings whereRecruiterJobId($value)
 * @method static Builder|JobRatings whereSeekerId($value)
 * @method static Builder|JobRatings whereSkills($value)
 * @method static Builder|JobRatings whereTeamwork($value)
 * @method static Builder|JobRatings whereTempJobId($value)
 * @method static Builder|JobRatings whereTimeManagement($value)
 * @method static Builder|JobRatings whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JobRatings extends Model
{
    protected $table = 'job_ratings';
    protected $primaryKey = 'id';
    
    protected $hidden       = ['created_at','updated_at'];
}