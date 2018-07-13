<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\Schooling;

class SchoolController extends Controller
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
        $schools = Schooling::where('parent_id',null)->where('is_active',1)->get();
        return view('cms.school.create',['schools'=>$schools]);
    }
    
    /**
     * List all locations.
     *
     * @param  array  $data
     * @return User
     */
    protected function index()
    {
        
        return view('cms.school.index');
    }
    
    /**
     * Show the form to update an existing location.
     *
     * @return Response
     */
    public function edit($id)
    {
        $school = Schooling::find($id);
        $parentSchools = Schooling::where('parent_id',null)->where('is_active',1)->get();
        return view('cms.school.update',['school' => $school , 'parentSchools' => $parentSchools]);
    }

    /**
     * Store a new/update location.
     *
     * @param  Request  $request
     * @return return to lisitng page
     */
    public function store(Request $request)
    {
        try{
        $reqData = $request->all();
        $rules = array(
            'parent_school' => array('required','integer'),
            'school' => array('required','unique:schoolings,school_name,parent_id')
        );
        
        if(isset($request->id)){
            $rules['parent_school'] = array('required','integer');
            $rules['school'] = "Required|Unique:schoolings,school_name,".$request->id.",id";
            $school = Schooling::find($request->id);  
            $msg = trans('messages.skill_updated');
        }
        else{
            $school = new Schooling;
            $msg = trans('messages.skill_added');
        }
        $validator = Validator::make($reqData, $rules);
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
        if($request->parent_school > 0){
            $school->parent_id = trim($request->parent_school);
        }
        $school->school_name = trim($request->school);
        $school->is_active = ($request->is_active)?1:0;
        $school->save();
        Session::flash('message',$msg);
        return redirect('cms/school/index');
        } catch(\Exception $e) {
            Session::flash('message',$e->getMessage());
        }
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

    public function schoolList(){
        try{
        $schools =  Schooling::leftJoin('schoolings as sc','sc.id','=','schoolings.parent_id')
                    ->select('schoolings.id','schoolings.school_name','schoolings.is_active','schoolings.parent_id','sc.school_name as parent_school_name')
                    ->orderBy('schoolings.id', 'desc');
                    
        
        return Datatables::of($schools)
                ->make(true);
        } catch(\Exception $e) {
            Session::flash('message',$e->getMessage());
        }
                       
    }
}
