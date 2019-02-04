<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterOfficeType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterOfficeType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterOfficeType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterOfficeType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterOfficeType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterOfficeType whereOfficeTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterOfficeType whereRecruiterOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterOfficeType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RecruiterOfficeType extends Model {

    protected $table = 'recruiter_office_types';
    protected $primaryKey = 'id';
    protected $fillable = ['recruiter_office_id', 'office_type_id'];

    public function officeTypes() {
        return $this->belongsTo(OfficeType::class, 'office_type_id');
    }

    public function recruiterOffice() {
        return $this->belongsTo(RecruiterOffice::class, 'recruiter_office_id');
    }

}
