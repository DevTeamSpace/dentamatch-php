<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends \Eloquent
{
    use Eloquence, Mappable;
    use SoftDeletes;
    
    protected $table        = 'states';
    protected $primaryKey   = 'id';
   
    protected $maps          = [
        'stateId' => 'id',
        'stateName' => 'state_name',
        'isActive'    => 'is_active',
        ];
    protected $hidden       = ['id','is_active','created_at','updated_at','deleted_at'];
    protected $fillable     = ['stateId','stateName','isActive'];
    protected $appends      = ['stateId','stateName','isActive'];

    protected $dates = ['deleted_at'];
    
}
