<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
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
     * Show the form to create a new location.
     *
     * @return Response 
     */
    public function create()
    {
        $skills = Skills::where('parent_id',0)->where('is_active',1)->where('skill_name', '!=','Other')->get();
        return view('cms.skill.create',['skills'=>$skills]);
    }
    
    /**
     * List all locations.
     *
     * @param  array  $data
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
        $parentSkills = Skills::where('parent_id',0)->where('is_active',1)->where('skill_name', '!=','Other')->get();
        return view('cms.skill.update',['skill' => $skill , 'parentskill' => $parentSkills]);
    }

    /**
     * Store a new/update location.
     *
     * @param  Request  $request
     * @return return to lisitng page
     */
    public function store(Request $request)
    {
        $rules = array(
            'parent_skill' => array('required','integer'),
            'skill' => array('required','unique:skills,skill_name,parent_id')
        );
        
        if(isset($request->id)){
            $rules['parent_skill'] = array('required','integer');
            $rules['skill'] = "Required|Unique:skills,skill_name,".$request->id.",id";
            $skill = Skills::find($request->id);  
            $msg = trans('messages.skill_updated');
        }
        else{
            $skill = new Skills;
            $msg = trans('messages.skill_added');
        }
        $this->validate($request, $rules);
        $skill->parent_id = trim($request->parent_skill);
        $skill->skill_name = trim($request->skill);
        $skill->is_active = ($request->is_active)?1:0;
        $skill->save();
        Session::flash('message',$msg);
        return redirect('cms/skill/index');
    }
    
    /**
     * Soft delete a location.
     *
     * @param  Location  $id
     * @return return to lisitng page
     */
    public function delete($id){
        Affiliation::findOrFail($id)->delete();
        Session::flash('message',trans('messages.location_deleted'));
        
    }

    public function skillList(){
        $skills =  Skills::leftJoin('skills as sk','sk.id','=','skills.parent_id')
                    ->select('skills.id','skills.skill_name','skills.is_active','skills.parent_id','sk.skill_name as parent_skill_name')
                    ->get();
        
        return Datatables::of($skills)
                ->removeColumn('id')
                ->removeColumn('parent_id')
                ->removeColumn('parent_skill_name')
                 ->addColumn('parent_skill', function ($skills) {
                	$parentSkill = ($skills->parent_skill_name) ? $skills->parent_skill_name:'';
                    return $parentSkill;
                })
                ->addColumn('active', function ($skills) {
                	$active = ($skills->is_active == 1) ? 'Yes':'No';
                    return $active;
                })
                ->addColumn('action', function ($skills) {
                    $edit = url('cms/skill/'.$skills->id.'/edit');
                    $delete =url('cms/skill/'.$skills->id.'/delete');
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
//                    $action .= '<a href="'.$delete.'" onclick="deleteRecord(this);return false;"  class="delete btn btn-xs btn-primary" onclick="return confirm(\'Are you sure you want to delete this location?\')"><i class="fa fa-remove"></i> Delete</a>';
                    return $action;
                       
                })
                ->make(true);
                       
    }
}
