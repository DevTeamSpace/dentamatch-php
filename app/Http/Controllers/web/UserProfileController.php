<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class UserProfileController extends Controller {

    public function createProfile(Request $request) {
        $validator = Validator::make($request->all(), [
                    'officeName' => 'required',
                    'officeDescription' => 'required',
                    'officeType' => 'required',
                    'officeAddress' => 'required',
                    'phoneNumber' => 'required|numeric|digits_between:9,10',
        ]);

        if ($validator->fails()) {
            return redirect('home')
                            ->withErrors($validator)
                            ->withInput();
        }
        //print_r($request->all());
        
    }
    

}
