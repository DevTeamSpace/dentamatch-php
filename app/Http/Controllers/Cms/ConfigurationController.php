<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Configs;
use Illuminate\Support\Facades\Storage;
use App\Repositories\File\FileRepositoryS3;
use Illuminate\Validation\ValidationException;

class ConfigurationController extends Controller
{
    use FileRepositoryS3;

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
     * Show the form to view a search radius
     *
     * @return Response
     */
    public function create()
    {
        $radius = Configs::select('config_data')->where('config_name', '=', 'SEARCHRADIUS')->first();
        return view('cms.config.radius', ['radius' => $radius]);
    }

    /**
     * Show the form to view a pay rate file
     *
     * @return Response
     */
    public function index()
    {
        $payrate = Configs::select('config_data')->where('config_name', '=', 'PAYRATE')->first();
        $payrateUrl = '';
        if ($payrate->config_data != null) {
            $payrateUrl = Storage::url($payrate->config_data);
        }
        return view('cms.config.pay-rate', ['payrateUrl' => $payrateUrl]);
    }


    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function updatePayrate(Request $request)
    {
        $rules = [
            'payrate' => 'required|max:2048|mimes:jpeg,bmp,png,jpg,pdf',
        ];

        $this->validate($request, $rules);

        $filename = $this->generateFilename('payrate');
        $this->uploadFileToAWS($request, $filename, 'payrate');
        Configs::where('config_name', 'PAYRATE')->update(['config_data' => $filename]);
        Session::flash('message', trans('messages.payrate_update'));
        return redirect('cms/config/pay-rate');
    }

    /**
     * Store a search radius
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'radius' => ['required'],
        ];
        $this->validate($request, $rules);

        Configs::where('config_name', 'SEARCHRADIUS')->update(['config_data' => $request->radius]);
        $msg = trans('messages.radius_update');
        Session::flash('message', $msg);
        return redirect('cms/config/create-radius');
    }
}
