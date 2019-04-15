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
     * Show the form to create a new Affiliation.
     *
     * @return Response
     */
    public function create()
    {
        return view('cms.affiliation.create');
    }

    /**
     * List all Affiliation.
     *
     * @return Response
     */
    protected function index()
    {
        return view('cms.affiliation.index');
    }

    /**
     * Show the form to update an existing Affiliation.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $affiliation = Affiliation::findOrFail($id);
        return view('cms.affiliation.update', ['affiliation' => $affiliation]);
    }

    /**
     * Store a new/update Affiliation.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'affiliation' => ['required', Rule::unique('affiliations', 'affiliation_name')->ignore($request->id)],
        ];

        $this->validate($request, $rules);

        $affiliation = $request->id ? Affiliation::find($request->id) : new Affiliation;
        $msg = $request->id ? trans('messages.affiliation_updated') : trans('messages.affiliation_added');

        $affiliation->affiliation_name = trim($request->affiliation);
        $affiliation->is_active = ($request->is_active) ? 1 : 0;
        $affiliation->save();
        Session::flash('message', $msg);
        return redirect('cms/affiliation/index');
    }

    /**
     * Delete affiliation
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        Affiliation::findOrFail($id)->delete();
        return WebResponse::successResponse(trans('messages.record_was_deleted'));
    }

    /**
     * List all Affiliations.
     *
     * @return Response
     * @throws \Exception
     */
    public function affiliationsList()
    {
        $affiliations = Affiliation::select(['affiliation_name', 'is_active', 'id'])->orderBy('id', SORT_DESC);
        return Datatables::of($affiliations)->make(true);
    }
}
