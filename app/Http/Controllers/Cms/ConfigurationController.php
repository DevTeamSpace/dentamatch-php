<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Session;
use App\Models\Configs;

class ConfigurationController extends Controller
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
        $radius = Configs::select('config_data')->where('config_name','=','SEARCHRADIUS')->first();
        return view('cms.config.radius',['radius' => $radius]);
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
            'radius' => array('required'),
        );
        $this->validate($request, $rules);
        Configs::where('config_name', 'SEARCHRADIUS')->update(['config_data' => $request->radius]);
        $msg = trans('messages.radius_update');
        Session::flash('message',$msg);
        return redirect('cms/config/create-radius');
    }
}
