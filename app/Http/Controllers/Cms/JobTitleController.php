<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\JobTitles;
use Log;
class JobTitleController extends Controller
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
        return view('cms.jobtitle.create');
    }
    
    /**
     * List all locations.
     *
     * @param  array  $data
     * @return User
     */
    protected function index()
    {  
        return view('cms.jobtitle.index');
    }
    
    /**
     * Show the form to update an existing location.
     *
     * @return Response
     */
    public function edit($id)
    {
        $jobtitle = JobTitles::find($id);
        
        return view('cms.jobtitle.update',['jobtitle'=>$jobtitle]);
    }

    /**
     * Store a new/update location.
     *
     * @param  Request  $request
     * @return return to lisitng page
     */
    public function store(Request $request)
    {
        // Validate and store the location...
        try{
        $reqData = $request->all();
        $rules = array(
            'jobtitle' => array('required','unique:job_titles,jobtitle_name'),
        );
        
        if(isset($request->id)){
            $rules['jobtitle'] = "Required|Unique:job_titles,jobtitle_name,".$request->id;
            $jobtitle = JobTitles::find($request->id);  
            $msg = trans('messages.jobtitle_updated');
        }
        else{
            $jobtitle = new JobTitles;
            $msg = trans('messages.jobtitle_added');
        }
            $msg = 
        
        $validator = Validator::make($reqData, $rules);
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
        
        $jobtitle->jobtitle_name = trim($request->jobtitle);
        $jobtitle->is_active = ($request->is_active)?1:0;
        $jobtitle->save();
        Session::flash('message',$msg);
        return redirect('cms/jobtitle/index');
        }  catch (\Exception $e) {
            Log::error($e);
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

    public function jobTitleList(){
        try{
        $jobtitles = JobTitles::SELECT('jobtitle_name','is_active','id')->orderBy('id', 'asc');
        return Datatables::of($jobtitles)
                ->make(true);
        }  catch (\Exception $e) {
            Log::error($e);
        }
                       
    }
}
