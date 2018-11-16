<?php

namespace App\Http\Controllers;

use App\H1b;
use App\Perm;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ListLcaController extends Controller
{
    public function index(Request $request, $company, $job = NULL)
    {
        $company = str_replace('-', ' ', $company);

    	$topcompanies = DB::table('companies')->get();

        $topjobs = DB::table('h1bs')
                ->select(DB::raw('JOB_TITLE as name, count(*) as count'))
                ->where('VISA_CLASS', '!=', 'E-3 Australian')
                ->groupBy('JOB_TITLE')
                ->orderBy('count','desc')
                ->limit(10)
                ->get();

    	$listlca_query = DB::table('h1bs')
    		->select(DB::raw('id, EMPLOYER_NAME, CASE_SUBMITTED, JOB_TITLE, PREVAILING_WAGE, WORKSITE_STATE'))
            ->where('VISA_CLASS', '!=', 'E-3 Australian');


    	if ($job != NULL) {
    		$listlca_query->where('JOB_TITLE', $job);
    	}
    			
    	$listlca = $listlca_query->whereRaw("MATCH (EMPLOYER_NAME) AGAINST ( '\"$company\"' IN BOOLEAN MODE)")
    			->orderBy('CASE_SUBMITTED','desc')
    			->paginate(100);

       if (!$request->session()->has($company)) {
            $data = array();
            foreach ($listlca as $item) {
                $i = rand(1, 6);
                if  ($i == 1 || $i == 2) {
                    array_push($data, $item->id);
                }
            }
            $collection = collect($data);
            $request->session()->put($company, $collection);
        }

    	return view('listlca', compact('listlca', 'topjobs', 'topcompanies', 'company'));
    }
}
