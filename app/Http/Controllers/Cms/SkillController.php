<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\WebResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\Datatables\Datatables;
use App\Models\Skills;

class SkillController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cms');
    }

    /**
     * Show the form to create a new skill.
     *
     * @return Response
     */
    public function create()
    {
        $skills = Skills::where('parent_id', 0)->where('is_active', 1)->where('skill_name', '!=', 'Other')->get();
        return view('cms.skill.create', ['skills' => $skills]);
    }

    /**
     * List all skills.
     *
     * @return Response
     */
    protected function index()
    {
        return view('cms.skill.index');
    }

    /**
     * Show the form to update an existing skill.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $skill = Skills::findOrFail($id);
        $parentSkills = Skills::where('parent_id', 0)->where('is_active', 1)->where('skill_name', '!=', 'Other')->get();
        return view('cms.skill.update', ['skill' => $skill, 'parentskill' => $parentSkills]);
    }

    /**
     * Store a new/update skill.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $reqData = $request->all();
        $rules = [
            'parent_skill' => ['required', 'integer'],
            'skill'        => ['required', Rule::unique('skills', 'skill_name')->ignore($request->id)]
        ];

        $this->validate($request, $rules);

        $skill = $request->id ? Skills::find($request->id) : new Skills;
        $msg = $request->id ? trans('messages.skill_updated') : trans('messages.skill_added');

        $skill->parent_id = trim($request->parent_skill);
        $skill->skill_name = trim($request->skill);
        $skill->is_active = ($request->is_active) ? 1 : 0;
        $skill->save();
        Session::flash('message', $msg);
        return redirect('cms/skill/index');
    }

    /**
     * Delete skill
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        Skills::findOrFail($id)->delete();
        return WebResponse::successResponse(trans('messages.record_was_deleted'));
    }

    /**
     * Method to get list of all skills
     * @return Response
     * @throws \Exception
     */
    public function skillList()
    {
        $skills = Skills::leftJoin('skills as sk', 'sk.id', '=', 'skills.parent_id')
            ->select('skills.id', 'skills.skill_name', 'skills.is_active', 'skills.parent_id', 'sk.skill_name as parent_skill_name')
            ->orderBy('skills.id', 'asc');
        return Datatables::of($skills)->make(true);
    }
}
