<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\Datatables\Datatables;

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
     * @return Response
     */
    protected function index()
    {
        return view('cms.location.index');
    }

    /**
     * Show the form to update an existing location.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $location = Location::findOrFail($id);
        return view('cms.location.update', ['location' => $location]);
    }

    /**
     * Store a new/update location.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'zipcode' => ['required', 'digits_between:5,8', 'regex:/[0-9]/',
                          Rule::unique('locations', 'zipcode')->ignore($request->id)],
        ];

        $this->validate($request, $rules);

        $location = $request->id ? Location::find($request->id) : new Location;
        $msg = $request->id ? trans('messages.location_updated') : trans('messages.location_added');

        $location->zipcode = trim($request->zipcode);
        $location->description = trim($request->description);
        $location->is_active = ($request->is_active) ? 1 : 0;
        $location->save();
        Session::flash('message', $msg);
        return redirect('cms/location/index');
    }

    /**
     * Method to get list of locations
     * @return Response
     */
    public function locationsList()
    {
        $locations = Location::select(['zipcode', 'free_trial_period', 'is_active', 'id'])->orderBy('id', 'desc');
        return Datatables::of($locations)->make(true);
    }
}
