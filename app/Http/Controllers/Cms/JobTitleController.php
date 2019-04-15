<?php

namespace App\Http\Controllers\Cms;

use App\Helpers\WebResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\Datatables\Datatables;
use App\Models\JobTitles;

class JobTitleController extends Controller
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
     * Show the form to create a new job title
     *
     * @return Response
     */
    public function create()
    {
        return view('cms.jobtitle.create');
    }

    /**
     * List all job titles.
     *
     * @return Response
     */
    protected function index()
    {
        return view('cms.jobtitle.index');
    }

    /**
     * Show the form to update job title.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $jobtitle = JobTitles::findOrFail($id);
        return view('cms.jobtitle.update', ['jobtitle' => $jobtitle]);
    }

    /**
     * Store a new/update job title.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'jobtitle' => ['required', Rule::unique('job_titles', 'jobtitle_name')->ignore($request->id)],
        ];

        $this->validate($request, $rules);

        $jobtitle = $request->id ? JobTitles::find($request->id) : new JobTitles;
        $msg = $request->id ? trans('messages.jobtitle_updated') : trans('messages.jobtitle_added');

        $jobtitle->jobtitle_name = trim($request->jobtitle);
        $jobtitle->is_active = ($request->is_active) ? 1 : 0;
        $jobtitle->save();
        Session::flash('message', $msg);
        return redirect('cms/jobtitle/index');
    }

    /**
     * Delete job title
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        JobTitles::findOrFail($id)->delete();
        return WebResponse::successResponse(trans('messages.record_was_deleted'));
    }

    /**
     * Method to get list of job titles
     * @return Response
     * @throws \Exception
     */
    public function jobTitleList()
    {
        $jobtitles = JobTitles::select(['jobtitle_name', 'is_active', 'id'])->orderBy('id', SORT_ASC);
        return Datatables::of($jobtitles)->make(true);
    }
}
