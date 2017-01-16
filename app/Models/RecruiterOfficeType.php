<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruiterOfficeType extends Model {

    protected $table = 'recruiter_office_types';
    protected $primaryKey = 'id';
    protected $fillable = ['recruiter_office_id', 'office_type_id'];

}
