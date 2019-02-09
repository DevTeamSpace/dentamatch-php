<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\JobseekerCertificates
 *
 * @property int $id
 * @property int $user_id
 * @property int $certificate_id
 * @property string $image_path
 * @property string|null $validity_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property User $user
 * @property Certifications $certificate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerCertificates whereValidityDate($value)
 * @mixin \Eloquent
 */
class JobseekerCertificates extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function certificate()
    {
        return $this->belongsTo(Certifications::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getJobSeekerCertificates($userId)
    {
        $returnData = [];
        $query = static::select('certificate_id', 'image_path', 'validity_date')
            ->where('user_id', $userId)
            ->orderBy('certificate_id');

        $list = $query->get()->toArray();
        if (!empty($list)) {
            foreach ($list as $value) {
                $returnData[$value['certificate_id']] = $value;
                $returnData[$value['certificate_id']]['image_path'] = ApiResponse::getThumbImage($value['image_path']);

            }
        }
        return $returnData;
    }

    public static function getParentJobSeekerCertificates($userId)
    {
        $certificates = [];
        if ($userId) {
            $certificates = static::where('jobseeker_certificates.user_id', $userId)
                ->leftJoin('certifications', 'jobseeker_certificates.certificate_id', '=', 'certifications.id')
                ->select('image_path', 'validity_date', 'certifications.certificate_name')
                ->where('certifications.is_active', 1)
                ->get()
                ->toArray();
        }
        return $certificates;
    }
}
