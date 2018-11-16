<?php

namespace App\Http\Controllers;

use App\H1b;
use App\Perm;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class TopJobsController extends Controller
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

    	$jobs = DB::table('h1bs')
    			->select(DB::raw('JOB_TITLE as name, count(*) as count, AVG(PREVAILING_WAGE) as average'))
                ->where('VISA_CLASS', '!=', 'E-3 Australian')
    			->orderBy('count','desc')
                ->groupBy('JOB_TITLE')
                ->limit(2000)
    			->get();

    	return view('topjobs', compact('jobs', 'topjobs', 'topcompanies'));
    }
}
