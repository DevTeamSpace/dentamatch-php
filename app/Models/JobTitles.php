<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTitles extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    protected $table = 'job_titles';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'JobtitleName' => 'jobtitle_name',
        'isLicenseRequired' => 'is_license_required',
        ];
    protected $hidden       = ['is_active','created_at','updated_at'];
    
    public static function getAll($active=''){
        $obj = self::select('id','jobtitle_name','is_license_required');
        if($active!=''){
            $obj->where('is_active',$active);
        }
        return $obj->get()->toArray();
    }

    public static function getTitle($titleId){
        $obj = self::select('jobtitle_name','is_license_required');
        $obj->where('id',$titleId);
        $obj->where('is_active',JobTitles::ACTIVE);
        return $obj->get()->toArray();
    }
}