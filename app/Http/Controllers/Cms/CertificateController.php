<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\Certifications;

class CertificateController extends Controller
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
        return view('cms.certificate.create');
    }
    
    /**
     * List all locations.
     *
     * @param  array  $data
     * @return User
     */
    protected function index()
    {  
        return view('cms.certificate.index');
    }
    
    /**
     * Show the form to update an existing location.
     *
     * @return Response
     */
    public function edit($id)
    {
        $certification = Certifications::find($id);
        
        return view('cms.certificate.update',['certificate'=>$certification]);
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
            'certificate' => array('required','unique:certifications,certificate_name'),
        );
        
        if(isset($request->id)){
            $rules['certificate'] = "Required|Unique:certifications,certificate_name,".$request->id;
            $certification = Certifications::find($request->id);  
            $msg = trans('messages.certification_updated');
        }
        else{
            $certification = new Certifications;
            $msg = trans('messages.certification_added');
        }
        
        $this->validate($request, $rules);
        
        $certification->certificate_name = trim($request->certificate);
        $certification->is_active = ($request->is_active)?1:0;
        $certification->save();
        Session::flash('message',$msg);
        return redirect('cms/certificate/index');
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

    public function certificationList(){
        $certificates = Certifications::SELECT(['certificate_name','is_active','id'])->get();
        return Datatables::of($certificates)
                ->removeColumn('id')
                ->addColumn('active', function ($certificates) {
                	$active = ($certificates->is_active == 1) ? 'Yes':'No';
                    return $active;
                })
                ->addColumn('action', function ($certificates) {
                    $edit = url('cms/certificate/'.$certificates->id.'/edit');
                    $delete =url('cms/certificate/'.$certificates->id.'/delete');
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
//                    $action .= '<a href="'.$delete.'" onclick="deleteRecord(this);return false;"  class="delete btn btn-xs btn-primary" onclick="return confirm(\'Are you sure you want to delete this location?\')"><i class="fa fa-remove"></i> Delete</a>';
                    return $action;
                       
                })
                ->make(true);
                       
    }
}