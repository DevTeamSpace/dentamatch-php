<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\Skills;
use Log;

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
     * Show the form to create a new location.
     *
     * @return Response
     */
    public function create()
    {
        $skills = Skills::where('parent_id', 0)->where('is_active', 1)->where('skill_name', '!=', 'Other')->get();
        return view('cms.skill.create', ['skills' => $skills]);
    }

    /**
     * List all locations.
     *
     * @param  array $data
     * @return User
     */
    protected function index()
    {

        return view('cms.skill.index');
    }

    /**
     * Show the form to update an existing location.
     *
     * @return Response
     */
    public function edit($id)
    {
        $skill = Skills::find($id);
        $parentSkills = Skills::where('parent_id', 0)->where('is_active', 1)->where('skill_name', '!=', 'Other')->get();
        return view('cms.skill.update', ['skill' => $skill, 'parentskill' => $parentSkills]);
    }

    /**
     * Store a new/update location.
     *
     * @param  Request $request
     * @return return to lisitng page
     */
    public function store(Request $request)
    {
        try {
            $reqData = $request->all();
            $rules = [
                'parent_skill' => ['required', 'integer'],
                'skill'        => ['required', 'unique:skills,skill_name,parent_id']
            ];

            if (isset($request->id)) {
                $rules['parent_skill'] = ['required', 'integer'];
                $rules['skill'] = "Required|Unique:skills,skill_name," . $request->id . ",id";
                $skill = Skills::find($request->id);
                $msg = trans('messages.skill_updated');
            } else {
                $skill = new Skills;
                $msg = trans('messages.skill_added');
            }
            $validator = Validator::make($reqData, $rules);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $skill->parent_id = trim($request->parent_skill);
            $skill->skill_name = trim($request->skill);
            $skill->is_active = ($request->is_active) ? 1 : 0;
            $skill->save();
            Session::flash('message', $msg);
            return redirect('cms/skill/index');
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Soft delete a location.
     *
     * @param  Location $id
     * @return return to lisitng page
     */
    public function delete($id)
    {
        Affiliation::findOrFail($id)->delete();
        Session::flash('message', trans('messages.location_deleted'));

    }

    /**
     * Method to get list of all skills
     * @return json
     */
    public function skillList()
    {
        try {
            $skills = Skills::leftJoin('skills as sk', 'sk.id', '=', 'skills.parent_id')
                ->select('skills.id', 'skills.skill_name', 'skills.is_active', 'skills.parent_id', 'sk.skill_name as parent_skill_name')
                ->orderBy('skills.id', 'asc');
            return Datatables::of($skills)
                ->make(true);
        } catch (\Exception $e) {
            Log::error($e);
        }

    }
}
