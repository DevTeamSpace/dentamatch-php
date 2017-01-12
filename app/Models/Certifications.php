<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certifications extends Model
{
    //
    protected $table = 'certifications';
    protected $primaryKey = 'id';
    
    
    protected $hidden       = ['created_at','updated_at','is_active'];
}
