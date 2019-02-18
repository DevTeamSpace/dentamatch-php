<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use App\Models\Certifications;
use App\Repositories\File\FileRepositoryS3;
use App\Models\JobseekerCertificates;

class CertificateApiController extends Controller
{
    use FileRepositoryS3;

    public function __construct()
    {
        $this->middleware('ApiAuth');
    }

    /**
     * Description : Get Certification Listing
     * Method : getCertifications
     * formMethod : GET
     * @param Request $request
     * @return Response
     */
    public function getCertifications(Request $request)
    {
        $userId = $request->apiUserId;
        $userCertification = JobseekerCertificates::where('user_id', '=', $userId)->get();
        $certificationList = Certifications::where('is_active', 1)->get()->toArray();
        $userCertificationData = [];

        if ($userCertification) {
            $userCertificationArray = $userCertification->toArray();
            foreach ($userCertificationArray as $key => $value) {
                $userCertificationData[$value['certificate_id']] = ['certificate_id' => $value['certificate_id'], 'validity_date' => $value['validity_date'], 'image_path' => $value['image_path']];
            }
        }
        $certificationArray = [];
        foreach ($certificationList as $key => $certificate) {
            $array = ['id' => $certificate['id'], 'certificateName' => $certificate['certificate_name'], 'validityDate' => '', 'imagePath' => ''];
            if (!empty($userCertificationData[$certificate['id']])) {
                $array['validityDate'] = $userCertificationData[$certificate['id']]['validity_date'];
                $array['imagePath'] = env('AWS_URL') . '/' . env('AWS_BUCKET') . '/' . $userCertificationData[$certificate['id']]['image_path'];
            }

            $certificationArray[] = $array;
        }
        return ApiResponse::successResponse('Certificate list', ['list' => $certificationArray]);
    }

    /**
     * Description : Update certifications
     * Method : updateCertifications
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function updateCertifications(Request $request)
    {
        $this->validate($request, [
            'certificateId' => 'required|integer',
            'image'         => 'required|mimes:jpeg,jpg,png|max:102400',
        ]);
        $userId = $request->apiUserId;
        $filename = $this->generateFilename('certificate');
        $response = $this->uploadFileToAWS($request, $filename, 'image');
        if ($response['res']) {
            JobseekerCertificates::updateOrCreate(
                ['user_id' => $userId, 'certificate_id' => $request->certificateId], ['image_path' => $response['file']]
            );
            $url['imgUrl'] = env('AWS_URL') . '/' . env('AWS_BUCKET') . '/' . $response['file'];
            ApiResponse::chkProfileComplete($userId);
            $response = ApiResponse::successResponse(trans("messages.certificate_successful_update"), $url);
        } else {
            $response = ApiResponse::errorResponse(trans("messages.upload_image_problem"));
        }

        return $response;
    }

    /**
     * Description : Update certifications validity date
     * Method : updateCertificationsValidity
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function updateCertificationsValidity(Request $request)
    {
        $this->validate($request, [
            'certificateValidition.*.value' => 'required_unless:certificateValidition.*.id,7|date',
        ]);
        $userId = $request->apiUserId;
        $reqData = $request->all();
        if (is_array($reqData['certificateValidition']) && count($reqData['certificateValidition']) > 0) {
            foreach ($reqData['certificateValidition'] as $value) {
                JobseekerCertificates::where('user_id', $userId)->where('certificate_id', $value['id'])->update(['validity_date' => $value['value']]);
            }
        }
        ApiResponse::chkProfileComplete($userId);
        return ApiResponse::successResponse(trans("messages.certificate_details_successful_update"));

        /*  } catch (ValidationException $e) {
              $messages = ['certificateValidition' => trans("messages.validity_date_empty")];
              $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
          } */
    }

}
