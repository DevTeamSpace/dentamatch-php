<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
use Log;

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
     * @param  array $data
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

        return view('cms.location.update', ['location' => $location]);
    }

    /**
     * Store a new/update location.
     *
     * @param  Request $request
     * @return return to lisitng page
     */
    public function store(Request $request)
    {
        // Validate and store the location...
        try {
            $reqData = $request->all();
            $rules = [
                'zipcode' => ['required', 'digits_between:5,8', 'regex:/[0-9]/', 'unique:locations,zipcode,NULL,id,deleted_at,NULL'],
            ];

            if (isset($request->id)) {
                $rules['zipcode'] = "Required|Between:5,8|regex:/[0-9]/|Unique:locations,zipcode," . $request->id;
                $location = Location::find($request->id);
                $msg = trans('messages.location_updated');
            } else {
                $location = new Location;
                $msg = trans('messages.location_added');
            }

            $validator = Validator::make($reqData, $rules);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $location->zipcode = trim($request->zipcode);
            $location->description = trim($request->description);
            //$location->free_trial_period = $request->free_trial_period;
            $location->is_active = ($request->is_active) ? 1 : 0;
            $location->save();
            Session::flash('message', $msg);
            return redirect('cms/location/index');
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
        Location::findOrFail($id)->delete();
        Session::flash('message', trans('messages.location_deleted'));

    }

    /**
     * Method to get list of locations
     * @return json
     */
    public function locationsList()
    {
        try {
            $locations = Location::SELECT(['zipcode', 'free_trial_period', 'is_active', 'id'])->orderBy('id', 'desc');

            return Datatables::of($locations)
                ->make(true);
        } catch (\Exception $e) {
            Log::error($e);
        }

    }
}
