<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\Datatables\Datatables;
use App\Models\OfficeType;

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
     * Show the form to create a new officetype.
     *
     * @return Response
     */
    public function create()
    {
        return view('cms.officetype.create');
    }

    /**
     * List all officetype.
     *
     * @return Response
     */
    protected function index()
    {
        return view('cms.officetype.index');
    }

    /**
     * Show the form to update an existing officetype.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $officeType = OfficeType::findOrFail($id);
        return view('cms.officetype.update', ['officetype' => $officeType]);
    }

    /**
     * Store a new/update officetype.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'officetype' => ['required', Rule::unique('office_types', 'officetype_name')->ignore($request->id)],
        ];

        $this->validate($request, $rules);

        $officeType = $request->id ? OfficeType::find($request->id) : new OfficeType;
        $msg = $request->id ? trans('messages.officetype_updated') : trans('messages.officetype_added');

        $officeType->officetype_name = trim($request->officetype);
        $officeType->is_active = ($request->is_active) ? 1 : 0;
        $officeType->save();
        Session::flash('message', $msg);
        return redirect('cms/officetype/index');
    }

    /**
     * Method to get list of officetype
     * @return Response
     */
    public function officeTypeList()
    {
        $officeTypes = OfficeType::select(['officetype_name', 'is_active', 'id'])->orderBy('id', 'desc');
        return Datatables::of($officeTypes)->make(true);
    }
}
