<?php

namespace App\Http\Controllers;

use App\H1b;
use App\Perm;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class TopCitiesController extends Controller
{
    public function index(Request $request) 
    {
    	$topcompanies = DB::table('companies')->get();

        $topjobs = DB::table('h1bs')
                ->select(DB::raw('JOB_TITLE as name, count(*) as count'))
                ->where('VISA_CLASS', '!=', 'E-3 Australian')
                ->groupBy('JOB_TITLE')
                ->orderBy('count','desc')
                ->limit(10)
                ->get();

    	$cities = DB::table('h1bs')
    			->select(DB::raw('WORKSITE_CITY as name, count(*) as count, AVG(PREVAILING_WAGE) as average'))
                ->where('VISA_CLASS', '!=', 'E-3 Australian')
    			->orderBy('count','desc')
                ->groupBy('WORKSITE_CITY')
                ->limit(2000)
    			->get();

    	return view('topcities', compact('cities', 'topjobs', 'topcompanies'));
    }
}
