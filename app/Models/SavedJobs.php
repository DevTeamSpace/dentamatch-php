<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavedJobs extends Model
{
    use SoftDeletes;
  
    protected $table  = 'saved_jobs';
    protected $primaryKey = 'id';
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
       'updated_at', 'created_at'
    ];
    
    
    
    
    

}
