<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobseekerCertificates extends Model
{
    //
    protected $table = 'jobseeker_certificates';
    protected $primaryKey = 'id';
    
    protected $guarded = array('id');
    protected $hidden       = ['created_at','updated_at','deleted_at'];
    
    public static function getJobSeekerCertificates($userId)
    {   
        $s3Url = env('AWS_URL');
        $s3Bucket = env('AWS_BUCKET');
        $query = static::select('certificate_name','certificate_id', 'image_path', 'validity_date')
                            ->join('certifications', 'certifications.id', '=', 'jobseeker_certificates.certificate_id')
                            ->where('user_id',$userId)
                            ->where('certifications.is_active',1)
                            ->orderBy('certificate_id');
        
        $list = $query->get()->toArray();
        if(!empty($list)) {
            foreach($list as $key=>$value) {
                $list[$key]['image_path'] = !empty($value['image_path']) ? $s3Url.DIRECTORY_SEPARATOR.$s3Bucket.$value['image_path'] : $value['image_path'];
            }
        }

        return $list;
    }
}
