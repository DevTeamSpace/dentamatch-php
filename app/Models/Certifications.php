<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Certifications
 *
 * @property int $id
 * @property string $certificate_name
 * @property int $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certifications newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certifications newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certifications query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certifications whereCertificateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certifications whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certifications whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certifications whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Certifications whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Certifications extends Model
{
    //
    protected $table = 'certifications';
    protected $primaryKey = 'id';
    
    
    protected $hidden       = ['created_at','updated_at'];
    
    public static function getAllCertificates()
    {   
        $returnData = [];
        $query = static::select('id', 'certificate_name')
                            ->where('is_active',1)
                            ->orderBy('id');
        
        $list = $query->get()->toArray();
        if(!empty($list)) {
            foreach($list as $value) {
                $returnData[$value['id']] = $value;
            }
        }
        return $returnData;
    }
}
