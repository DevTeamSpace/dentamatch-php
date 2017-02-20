<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeType extends Model {

    protected $table = 'office_types';
    protected $primaryKey = 'id';
    protected $fillable = ['officetype_name', 'is_acitve'];
    
    public static function allOfficeTypes(){
        return OfficeType::get();
    }

}
