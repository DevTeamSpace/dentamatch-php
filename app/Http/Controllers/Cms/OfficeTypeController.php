<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\OfficeType;
use Log;
class OfficeTypeController extends Controller
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
        return view('cms.officetype.create');
    }
    
    /**
     * List all locations.
     *
     * @param  array  $data
     * @return User
     */
    protected function index()
    {  
        return view('cms.officetype.index');
    }
    
    /**
     * Show the form to update an existing location.
     *
     * @return Response
     */
    public function edit($id)
    {
        $officeType = OfficeType::find($id);
        
        return view('cms.officetype.update',['officetype'=>$officeType]);
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
        $rules = array(
            'officetype' => array('required','unique:office_types,officetype_name'),
        );
        
        if(isset($request->id)){
            $rules['officetype'] = "Required|Unique:office_types,officetype_name,".$request->id;
            $officeType = OfficeType::find($request->id);  
            $msg = trans('messages.officetype_updated');
        }
        else{
            $officeType = new OfficeType;
            $msg = trans('messages.officetype_added');
        }
        
        $this->validate($request, $rules);
        
        $officeType->officetype_name = trim($request->officetype);
        $officeType->is_active = ($request->is_active)?1:0;
        $officeType->save();
        Session::flash('message',$msg);
        return redirect('cms/officetype/index');
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

    public function officeTypeList(){
        try{
        $officeTypes = OfficeType::SELECT(['officetype_name','is_active','id'])->orderBy('id', 'desc')->get();
        return Datatables::of($officeTypes)
                ->removeColumn('id')
                ->addColumn('active', function ($officeTypes) {
                	$active = ($officeTypes->is_active == 1) ? 'Yes':'No';
                    return $active;
                })
                ->addColumn('action', function ($officeTypes) {
                    $edit = url('cms/officetype/'.$officeTypes->id.'/edit');
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
                    return $action;
                       
                })
                ->make(true);
        }  catch (\Exception $e) {
            Log::error($e);
        }
                       
    }
}
