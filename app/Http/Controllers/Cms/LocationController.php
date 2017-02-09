<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;

class LocationController extends Controller
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
        return view('cms.location.create');
    }
    
    /**
     * List all locations.
     *
     * @param  array  $data
     * @return User
     */
    protected function index()
    {  
        return view('cms.location.index');
    }
    
    /**
     * Show the form to update an existing location.
     *
     * @return Response
     */
    public function edit($id)
    {
        $location = Location::find($id);
        
        return view('cms.location.update',['location'=>$location]);
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
        $rules = array(
            'zipcode' => array('required','digits_between:5,8','regex:/[0-9]/','unique:locations,zipcode,NULL,id,deleted_at,NULL'),
        );
        
        if(isset($request->id)){
            $rules['zipcode'] = "Required|Between:5,8|regex:/[0-9]/|Unique:locations,zipcode,".$request->id;
            $location = Location::find($request->id);  
            $msg = trans('messages.location_updated');
        }
        else{
            $location = new Location;
            $msg = trans('messages.location_added');
        }
        
        $this->validate($request, $rules);
        
        $location->zipcode = trim($request->zipcode);
        $location->description = trim($request->description);
        $location->free_trial_period = $request->free_trial_period;
        $location->is_active = ($request->is_active)?1:0;
        $location->save();
        Session::flash('message',$msg);
        return redirect('cms/location/index');
    }
    
    /**
     * Soft delete a location.
     *
     * @param  Location  $id
     * @return return to lisitng page
     */
    public function delete($id){
        Location::findOrFail($id)->delete();
        Session::flash('message',trans('messages.location_deleted'));
        
    }

    public function locationsList(){
        $locations = Location::SELECT(['zipcode','free_trial_period','is_active','id'])->get();
        
        return Datatables::of($locations)
                ->removeColumn('id')
                ->addColumn('active', function ($locations) {
                	$active = ($locations->isActive) ? 'Yes':'No';
                    return $active;
                })
                ->addColumn('action', function ($locations) {
                    $edit = url('cms/location/'.$locations->id.'/edit');
                    $delete =url('cms/location/'.$locations->id.'/delete');
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
//                    $action .= '<a href="'.$delete.'" onclick="deleteRecord(this);return false;"  class="delete btn btn-xs btn-primary" onclick="return confirm(\'Are you sure you want to delete this location?\')"><i class="fa fa-remove"></i> Delete</a>';
                    return $action;
                       
                })
                ->make(true);
                       
    }
}
