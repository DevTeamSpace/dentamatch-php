<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use App\Models\Skills;
use App\Models\JobSeekerSkills;

class SkillApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['ApiAuth', 'ApiLog']);
    }

    /**
     * Description : Show skill lists with user skill
     * Method : getSkills
     * formMethod : GET
     * @param Request $request
     * @return Response
     */
    public function getSkills(Request $request)
    {
        $userId = $request->apiUserId;
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
        return ApiResponse::successResponse('Skill list', ['list' => $update_skills]);
    }

    /**
     * Description : Update user skills
     * Method : postSkills
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postSkills(Request $request)
    {
        $this->validate($request, [
            'skills' => 'sometimes',
            'other'  => 'sometimes',
        ]);
        $reqData = $request->all();
        $userId = $request->apiUserId;
        JobSeekerSkills::where('user_id', '=', $userId)->delete();

        if (is_array($reqData['skills']) && count($reqData['skills']) > 0) {
            $jobseekerSkills = [];
            foreach ($reqData['skills'] as $skill) {
                $jobseekerSkills[] = ['user_id' => $userId, 'skill_id' => $skill, 'other_skill' => ''];
            }
            JobSeekerSkills::insert($jobseekerSkills);
        }

        if (is_array($reqData['other']) && count($reqData['other']) > 0) {
            $jobseekerOtherSkills = [];
            foreach ($reqData['other'] as $otherSkill) {
                $jobseekerOtherSkills[] = ['user_id' => $userId, 'skill_id' => $otherSkill['id'], 'other_skill' => $otherSkill['value']];
            }
            JobSeekerSkills::insert($jobseekerOtherSkills);
        }
        ApiResponse::chkProfileComplete($userId);
        return ApiResponse::successResponse(trans("messages.skill_add_success"));
    }
}
