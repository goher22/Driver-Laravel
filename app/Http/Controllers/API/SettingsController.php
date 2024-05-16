<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    
    /**
     * Get settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
    	$settings = \Setting::all();

    	return response()->json($settings);
    }
}
