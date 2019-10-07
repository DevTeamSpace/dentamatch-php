<?php

namespace App\Http\Controllers\Cms;

use App\Models\PromoCode;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

class PromoCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('cms');
    }

    /**
     * List all promo codes.
     *
     * @return Response
     */
    protected function index()
    {
        return view('cms.promocode.index');
    }

    /**
     * Method to get list of promo codes
     * @return Response
     * @throws \Exception
     */
    public function promocodeList()
    {
        $query = PromoCode::query()->latest();
        return Datatables::of($query)->make(true);
    }
}
