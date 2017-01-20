<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certifications extends Model
{
    //
    protected $table = 'certifications';
    protected $primaryKey = 'id';
    
    
    protected $hidden       = ['created_at','updated_at','is_active'];
    
    public static function getAllCertificates()
    {   
        $returnData = [];
        $query = static::select('id', 'certificate_name')
                            ->where('is_active',1)
                            ->orderBy('id');
        
        $list = $query->get()->toArray();
        if(!empty($list)) {
            foreach($list as $key=>$value) {
                $returnData[$value['id']] = $value;
            }
        }
        return $returnData;
    }
}
