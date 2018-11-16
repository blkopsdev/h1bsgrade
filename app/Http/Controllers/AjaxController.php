<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{
    public function index(Request $request){
    	Log::debug($request);
    	$msg = "success";
    	$request->session()->push($request->company, $request->id);
    	return response()->json(array('msg'=> $msg), 200);
   }
}
