<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\RecruiterOfficeType
 *
 * @property int $id
 * @property int $recruiter_office_id
 * @property int $office_type_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\OfficeType $officeTypes
 * @property-read \App\Models\RecruiterOffice $recruiterOffice
 * @method static Builder|RecruiterOfficeType newModelQuery()
 * @method static Builder|RecruiterOfficeType newQuery()
 * @method static Builder|RecruiterOfficeType query()
 * @method static Builder|RecruiterOfficeType whereCreatedAt($value)
 * @method static Builder|RecruiterOfficeType whereId($value)
 * @method static Builder|RecruiterOfficeType whereOfficeTypeId($value)
 * @method static Builder|RecruiterOfficeType whereRecruiterOfficeId($value)
 * @method static Builder|RecruiterOfficeType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RecruiterOfficeType extends Model
{
    protected $table = 'recruiter_office_types';
    protected $primaryKey = 'id';
    protected $fillable = ['recruiter_office_id', 'office_type_id'];

    public function officeTypes()
    {
        return $this->belongsTo(OfficeType::class, 'office_type_id');
    }

    public function recruiterOffice()
    {
        return $this->belongsTo(RecruiterOffice::class, 'recruiter_office_id');
    }

}
