<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\Affiliation;

class AffiliationController extends Controller
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
        return view('cms.affiliation.create');
    }
    
    /**
     * List all locations.
     *
     * @param  array  $data
     * @return User
     */
    protected function index()
    {  
        return view('cms.affiliation.index');
    }
    
    /**
     * Show the form to update an existing location.
     *
     * @return Response
     */
    public function edit($id)
    {
        $affiliation = Affiliation::find($id);
        
        return view('cms.affiliation.update',['affiliation'=>$affiliation]);
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
            'affiliation' => array('required','unique:affiliations,affiliation_name'),
        );
        
        if(isset($request->id)){
            $rules['affiliation'] = "Required|Unique:affiliations,affiliation_name,".$request->id;
            $affiliation = Affiliation::find($request->id);  
            $msg = trans('messages.affiliation_updated');
        }
        else{
            $affiliation = new Affiliation;
            $msg = trans('messages.affiliation_added');
        }
        
        $this->validate($request, $rules);
        
        $affiliation->affiliation_name = trim($request->affiliation);
        $affiliation->is_active = ($request->is_active)?1:0;
        $affiliation->save();
        Session::flash('message',$msg);
        return redirect('cms/affiliation/index');
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

    public function affiliationsList(){
        $affiliations = Affiliation::SELECT(['affiliation_name','is_active','id'])->orderBy('id', 'desc')->get();
        return Datatables::of($affiliations)
                ->removeColumn('id')
                ->addColumn('active', function ($affiliations) {
                	$active = ($affiliations->is_active == 1) ? 'Yes':'No';
                    return $active;
                })
                ->addColumn('action', function ($affiliations) {
                    $edit = url('cms/affiliation/'.$affiliations->id.'/edit');
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
                    return $action;
                       
                })
                ->make(true);
                       
    }
}
