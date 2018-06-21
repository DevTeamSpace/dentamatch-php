<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\apiResponse;


class JobseekerCertificates extends Model
{   
    protected $table = 'jobseeker_certificates';
    protected $primaryKey = 'id';
    
    protected $guarded = array('id');
    protected $hidden       = ['created_at','updated_at','deleted_at'];
    
    public static function getJobSeekerCertificates($userId)
    {   
        $returnData = [];
        $query = static::select('certificate_id', 'image_path', 'validity_date')
                            ->where('user_id',$userId)
                            ->orderBy('certificate_id');
        
        $list = $query->get()->toArray();
        if(!empty($list)) {
            foreach($list as $value) {
                $returnData[$value['certificate_id']] = $value;
                $returnData[$value['certificate_id']]['image_path'] =  apiResponse::getThumbImage($value['image_path']);
                
            }
        }
        return $returnData;
    }

    public static function getParentJobSeekerCertificates($userId){
        $certificates = [];
        if($userId){
            $certificates = static::where('jobseeker_certificates.user_id',$userId)
                            ->leftJoin('certifications','jobseeker_certificates.certificate_id','=','certifications.id')
                            ->select('image_path','validity_date', 'certifications.certificate_name')
                            ->where('certifications.is_active',1)
                            ->get()
                            ->toArray();
        }
        return $certificates;
    }
}
