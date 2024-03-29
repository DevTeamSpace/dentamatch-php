<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use App\Models\Skills;
use App\Models\JobSeekerSkills;
use App\Models\Certifications;
use App\Repositories\File\FileRepositoryS3;
use App\Models\JobseekerCertificates;

class SkillApiController extends Controller
{
    use FileRepositoryS3;

    public function __construct()
    {
        $this->middleware('ApiAuth');
    }

    /**
     * Description : Show skill lists with user skill
     * Method : getSkilllists
     * formMethod : GET
     * @param Request $request
     * @return type
     */
    public function getSkilllists(Request $request)
    {
        try {
            $userId = $request->userServerData->user_id;
            if ($userId > 0) {
                $skillArray = [];
                $jobseekerSkills = JobSeekerSkills::where('user_id', $userId)->get();
                $UpdatedJobseekerSkills = [];
                if ($jobseekerSkills) {
                    $skillArray = $jobseekerSkills->toArray();
                    $userSkills = array_map(function ($value) {
                        return $value['skill_id'];
                    }, $skillArray);
                    foreach ($skillArray as $skill) {
                        $UpdatedJobseekerSkills[$skill['skill_id']] = ['skill_id' => $skill['skill_id'], 'other_skill' => $skill['other_skill']];
                    }
                }
                $skillLists = Skills::where('parent_id', 0)
                    ->where('is_active', 1)
                    ->with(['children' => function ($query) {
                        $query->orderBy('id', 'asc');
                    }])
                    ->get()
                    ->toArray();
                $update_skills = [];
                foreach ($skillLists as $key => $skill) {
                    if ($skill['skill_name'] != 'Other') {
                        $subskills = [];
                        $child_skill = [];
                        if (is_array($skill['children']) && count($skill['children']) > 0) {
                            foreach ($skill['children'] as $subskills) {
                                if (in_array($subskills['id'], $userSkills)) {
                                    $userSkill = 1;
                                } else {
                                    $userSkill = 0;
                                }
                                $subSkills = [
                                    'id'         => $subskills['id'],
                                    'parent_id'  => $subskills['parent_id'],
                                    'skill_name' => $subskills['skill_name'],
                                    'user_skill' => $userSkill,
                                ];
                                if (trim($subskills['skill_name']) == 'Other' || trim($subskills['skill_name']) == 'other') {
                                    $subSkills['other_skill'] = '';
                                    if ($userSkill == 1 && !empty($UpdatedJobseekerSkills[$subskills['id']])) {
                                        $subSkills['other_skill'] = $UpdatedJobseekerSkills[$subskills['id']]['other_skill'];
                                    }
                                }
                                $child_skill[] = $subSkills;
                            }
                        }
                        $update_skills[$key] = ['id' => $skill['id'], 'parent_id' => $skill['parent_id'], 'skill_name' => $skill['skill_name'], 'children' => $child_skill];
                    } else {
                        $otherSkill = "";
                        if (!empty($UpdatedJobseekerSkills[$skill['id']])) {
                            $otherSkill = $UpdatedJobseekerSkills[$skill['id']]['other_skill'];
                        }
                        $update_skills[$key] = ['id' => $skill['id'], 'parent_id' => $skill['parent_id'], 'skill_name' => $skill['skill_name'], 'other_skill' => $otherSkill, 'children' => []];
                    }
                }
                $response = ApiResponse::customJsonResponseObject(1, 200, "Skill list", 'list', ApiResponse::convertToCamelCase($update_skills));
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

    /**
     * Description : Update user skills
     * Method : postUpdateUserSkills
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postUpdateSkills(Request $request)
    {
        try {
            $this->validate($request, [
                'skills' => 'sometimes',
                'other'  => 'sometimes',
            ]);
            $reqData = $request->all();
            $userId = $request->userServerData->user_id;
            if ($userId > 0) {
                JobSeekerSkills::where('user_id', '=', $userId)->forceDelete();
                $jobseekerSkills = [];
                $jobseekerOtherSkills = [];
                if (is_array($reqData['skills']) && count($reqData['skills']) > 0) {
                    foreach ($reqData['skills'] as $skill) {
                        $jobseekerSkills[] = ['user_id' => $userId, 'skill_id' => $skill, 'other_skill' => ''];
                    }
                    JobSeekerSkills::insert($jobseekerSkills);
                }
                if (is_array($reqData['other']) && count($reqData['other']) > 0) {
                    foreach ($reqData['other'] as $otherSkill) {
                        $jobseekerOtherSkills[] = ['user_id' => $userId, 'skill_id' => $otherSkill['id'], 'other_skill' => $otherSkill['value']];
                    }
                    JobSeekerSkills::insert($jobseekerOtherSkills);
                }
                ApiResponse::chkProfileComplete($userId);
                $response = ApiResponse::customJsonResponse(1, 200, trans("messages.skill_add_success"));
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $e->errors()]);
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

    /**
     * Description : Get Certification Listing
     * Method : postUpdateUserSkills
     * formMethod : GET
     * @param
     * @return type
     */
    public function getCertificationListing(Request $request)
    {
        try {
            $userId = $request->userServerData->user_id;
            if ($userId > 0) {
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
                $response = ApiResponse::customJsonResponseObject(1, 200, "Certificate list", 'list', $certificationArray);
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $e->errors()]);
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

    /**
     * Description : Update certifications
     * Method : postUpdateCertifications
     * formMethod : POST
     * @param
     * @return type
     */
    public function postUpdateCertifications(Request $request)
    {
        try {
            $this->validate($request, [
                'certificateId' => 'required|integer',
                'image'         => 'required|mimes:jpeg,jpg,png|max:102400',
            ]);
            $userId = $request->userServerData->user_id;
            if ($userId > 0) {
                $filename = $this->generateFilename('certificate');
                $response = $this->uploadFileToAWS($request, $filename, 'image');
                if ($response['res']) {
                    JobseekerCertificates::updateOrCreate(
                        ['user_id' => $userId, 'certificate_id' => $request->certificateId], ['image_path' => $response['file']]
                    );
                    $url['imgUrl'] = env('AWS_URL') . '/' . env('AWS_BUCKET') . '/' . $response['file'];
                    ApiResponse::chkProfileComplete($userId);
                    $response = ApiResponse::customJsonResponse(1, 200, trans("messages.certificate_successful_update"), $url);
                } else {
                    $response = ApiResponse::responseError(trans("messages.upload_image_problem"));
                }
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $e->errors()]);
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

    /**
     * Description : Update certifications validity date
     * Method : postUpdateCertificationsValidity
     * formMethod : POST
     * @param
     * @return type
     */
    public function postUpdateCertificationsValidity(Request $request)
    {
        try {
            $this->validate($request, [
                'certificateValidition.*.value' => 'required_unless:certificateValidition.*.id,7|date',
            ]);
            $userId = $request->userServerData->user_id;
            $reqData = $request->all();
            if ($userId > 0) {
                if (is_array($reqData['certificateValidition']) && count($reqData['certificateValidition']) > 0) {
                    foreach ($reqData['certificateValidition'] as $value) {
                        JobseekerCertificates::where('user_id', $userId)->where('certificate_id', $value['id'])->update(['validity_date' => $value['value']]);
                    }
                }
                ApiResponse::chkProfileComplete($userId);
                $response = ApiResponse::customJsonResponse(1, 200, trans("messages.certificate_details_successful_update"));
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $messages = ['certificateValidition' => trans("messages.validity_date_empty")];
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

}
