<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\apiResponse;

class JobseekerCertificates extends Model
{
    //
    protected $table = 'jobseeker_certificates';
    protected $primaryKey = 'id';
    
    protected $guarded = array('id');
    protected $hidden       = ['created_at','updated_at','deleted_at'];
    
    public static function getJobSeekerCertificates($userId)
    {   
        $returnData = [];
        $s3Url = env('AWS_URL');
        $s3Bucket = env('AWS_BUCKET');
        $query = static::select('certificate_id', 'image_path', 'validity_date')
                            ->where('user_id',$userId)
                            ->orderBy('certificate_id');
        
        $list = $query->get()->toArray();
        if(!empty($list)) {
            foreach($list as $key=>$value) {
                $returnData[$value['certificate_id']] = $value;
                //$returnData[$value['certificate_id']]['image_path'] = !empty($value['image_path']) ? $s3Url.DIRECTORY_SEPARATOR.$s3Bucket.DIRECTORY_SEPARATOR.$value['image_path'] : $value['image_path'];
                $returnData[$value['certificate_id']]['image_path'] =  apiResponse::getThumbImage($value['image_path']);
            }
        }
        return $returnData;
    }
}
