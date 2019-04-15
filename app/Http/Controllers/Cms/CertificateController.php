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
     * Show the form to create a new certificate.
     *
     * @return Response
     */
    public function create()
    {
        return view('cms.certificate.create');
    }

    /**
     * List all certificates.
     *
     * @return Response
     */
    protected function index()
    {
        return view('cms.certificate.index');
    }

    /**
     * Show the form to update an existing certificate.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $certification = Certifications::findOrFail($id);
        return view('cms.certificate.update', ['certificate' => $certification]);
    }

    /**
     * Store a new/update certificate.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'certificate' => ['required', Rule::unique('certifications', 'certificate_name')->ignore($request->id)],
        ];

        $this->validate($request, $rules);

        $certification = $request->id ? Certifications::find($request->id) : new Certifications;
        $msg = $request->id ? trans('messages.certification_updated') : trans('messages.certification_added');

        $certification->certificate_name = trim($request->certificate);
        $certification->is_active = ($request->is_active) ? 1 : 0;
        $certification->save();
        Session::flash('message', $msg);
        return redirect('cms/certificate/index');

    }

    /**
     * Delete certificate
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        Certifications::findOrFail($id)->delete();
        return WebResponse::successResponse(trans('messages.record_was_deleted'));
    }

    /**
     * Method to get list of certification
     * @return Response
     * @throws \Exception
     */
    public function certificationList()
    {
        $certificates = Certifications::select(['certificate_name', 'is_active', 'id'])->orderBy('id', SORT_DESC);
        return Datatables::of($certificates)->make(true);
    }
}
