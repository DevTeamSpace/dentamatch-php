<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavoriteJobseekerController extends Controller {

    public function getFavJobseeker(Request $request) {
        
        return view('web.fav_jobseeker');
    }
    
    public function postFavJobseeker(Request $request){
        
    }

}
