<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\Datatables\Datatables;
use App\Models\Schooling;

class SchoolController extends Controller
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
     * Show the form to create a new school.
     *
     * @return Response
     */
    public function create()
    {
        $schools = Schooling::where('parent_id', null)->where('is_active', 1)->get();
        return view('cms.school.create', ['schools' => $schools]);
    }

    /**
     * List all schools.
     *
     * @return Response
     */
    protected function index()
    {
        return view('cms.school.index');
    }

    /**
     * Show the form to update an existing school.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $school = Schooling::findOrFail($id);
        $parentSchools = Schooling::where('parent_id', null)->where('is_active', 1)->get();
        return view('cms.school.update', ['school' => $school, 'parentSchools' => $parentSchools]);
    }

    /**
     * Store a new/update school.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'parent_school' => ['required', 'integer'],
            'school'        => ['required', Rule::unique('schoolings', 'school_name')->ignore($request->id)]
        ];

        $this->validate($request, $rules);

        $school = $request->id ? Schooling::find($request->id) : new Schooling;
        $msg = $request->id ? trans('messages.school_updated') : trans('messages.school_added');

        if ($request->parent_school > 0) {
            $school->parent_id = trim($request->parent_school);
        }
        $school->school_name = trim($request->school);
        $school->is_active = ($request->is_active) ? 1 : 0;
        $school->save();
        Session::flash('message', $msg);
        return redirect('cms/school/index');
    }

    /**
     * Method to get list of all schools
     * @return Response
     */
    public function schoolList()
    {
        $schools = Schooling::leftJoin('schoolings as sc', 'sc.id', '=', 'schoolings.parent_id')
            ->select(['schoolings.id', 'schoolings.school_name', 'schoolings.is_active', 'schoolings.parent_id', 'sc.school_name as parent_school_name'])
            ->orderBy('schoolings.id', 'desc');

        return Datatables::of($schools)->make(true);
    }
}
