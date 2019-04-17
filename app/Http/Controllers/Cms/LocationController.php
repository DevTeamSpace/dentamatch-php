<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\WebResponse;
use App\Models\PreferredJobLocation;
use App\Utils\LocationUtils;
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
    private $locationUtils;

    /**
     * Create a new controller instance.
     *
     * @param LocationUtils $utils
     */
    public function __construct(LocationUtils $utils)
    {
        $this->middleware('cms');
        $this->locationUtils = $utils;
    }

    /**
     * List all areas.
     *
     * @return Response
     */
    protected function index()
    {
        return view('cms.area.index');
    }

    /**
     * Show the form to create a new area.
     *
     * @return Response
     */
    public function create()
    {
        return view('cms.area.create');
    }

    /**
     * Show the form to update an existing area.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $location = PreferredJobLocation::findOrFail($id);
        return view('cms.area.update', ['location' => $location]);
    }

    /**
     * Store a new/update area.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $locationId = request('id');
        $rules = [
            'anchor_zipcode' => ['required', 'digits_between:5,8', 'regex:/[0-9]/',
            Rule::unique('preferred_job_locations', 'anchor_zipcode')->ignore($locationId)],
        ];

        $this->validate($request, $rules);

        $location = $locationId ? PreferredJobLocation::find($locationId) : new PreferredJobLocation;
        $msg = $locationId ? trans('messages.location_updated') : trans('messages.location_added');

        $location->preferred_location_name = trim(request('preferred_location_name'));
        $location->anchor_zipcode = trim(request('anchor_zipcode'));
        $location->radius = trim(request('radius'));
        $location->is_active = (request('is_active')) ? 1 : 0;
        $updateIndexes = $location->isDirty(['radius', 'anchor_zipcode']);

        $location->save();

        if ($updateIndexes) {
            $newIndexes = $this->locationUtils->getIndexesByRadius($location->anchor_zipcode, $location->radius);
            $ids = [];
            foreach ($newIndexes as $zipData) {
                $zipLocation = Location::firstOrNew(['zipcode' => array_get($zipData, 'Code')]);
                $zipLocation->zipcode = array_get($zipData, 'Code');
                $zipLocation->city = array_get($zipData, 'City');
                $zipLocation->state = array_get($zipData, 'State');
                $zipLocation->county = array_get($zipData, 'County');
                $zipLocation->latitude = array_get($zipData, 'Latitude');
                $zipLocation->longitude = array_get($zipData, 'Longitude');
                $zipLocation->distance = array_get($zipData, 'Distance', 0);
                $zipLocation->description = join(', ', [$zipLocation->city, $zipLocation->state]);
                $zipLocation->area_id = $location->id;
                $zipLocation->save();
                $ids[] = $zipLocation->id;
            }
            Location::query()->where('area_id', $location->id)->whereNotIn('id', $ids)->delete();
        }


        Location::query()->where('area_id', $location->id)->update(['is_active' => $location->is_active]);

        Session::flash('message', $msg);
        return redirect('cms/area/index');
    }

    /**
     * Delete area
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        PreferredJobLocation::findOrFail($id)->delete();
        return WebResponse::successResponse(trans('messages.record_was_deleted'));
    }

    /**
     * Method to get list of areas
     * @return Response
     * @throws \Exception
     */
    public function areaList()
    {
        $locations = PreferredJobLocation::query()->withCount('locations')->orderBy('id', SORT_DESC);
        return Datatables::of($locations)->make(true);
    }

    /**
     * Method to get list of locations
     * @return Response
     * @throws \Exception
     */
    public function locationIndex($id)
    {
        return view('cms.location.index', ['areaId' => $id]);
    }

    /**
     * Method to get list of locations
     * @return Response
     * @throws \Exception
     */
    public function locationList($id)
    {
        $locations = Location::query()->where('area_id', $id)->orderBy('id', SORT_DESC);
        return Datatables::of($locations)->make(true);
    }
}
