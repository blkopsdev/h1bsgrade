<?php

namespace App\Http\Controllers;

use App\H1b;
use App\Perm;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function show(Request $request) {
    	$request->flash();

    	$topcompanies = DB::table('companies')->get();

    	$topjobs = DB::table('h1bs')
    			->select(DB::raw('JOB_TITLE as name, count(*) as count'))
    			->where('VISA_CLASS', '!=', 'E-3 Australian')
    			->groupBy('JOB_TITLE')
    			->orderBy('count','desc')
    			->limit(10)
    			->get();

    	return view('welcome')->with(compact('topjobs', 'topcompanies'));
    }

    public function formsubmit(Request $request) {
		if (is_null($request->employer)) {
			$e = 'e';
		}
		else {
			$e = trim($request->employer);
		}

		if (is_null($request->job)) {
			$j= 'j';
		}
		else {
			$j = trim($request->job);
		}

		if (is_null($request->city)) {
			$c = 'c';
		}
		else {
			$c = trim($request->city);
		}

		if ($request->year == 'All Years') {
			$y = 'y';
		}
		else {
			$y = trim($request->year);
		}

		return redirect()->route('/', ['e' => str_replace(' ', '-', $e), 'j' => str_replace(' ', '-', $j), 'c' => str_replace(' ', '-', $c), 'y' => str_replace(' ', '-', $y)]);
    }

	public function index(Request $request, $e, $j, $c, $y) {

		$e = trim(str_replace('-', ' ', $e));
		$j = trim(str_replace('-', ' ', $j));
		$c = trim(str_replace('-', ' ', $c));
		$y = trim(str_replace('-', ' ', $y));
		

    	$topcompanies = DB::table('companies')->get();

    	$topjobs = DB::table('h1bs')
    			->select(DB::raw('JOB_TITLE as name, count(*) as count'))
    			->where('VISA_CLASS', '!=', 'E-3 Australian')
    			->groupBy('JOB_TITLE')
    			->orderBy('count','desc')
    			->limit(10)
    			->get();

    	if ($e == 'e' && $j == 'j' && $c == 'c') {
    		$records = NULL;
    		$e = NULL;
    		$j = NULL;
    		$c = NULL;
    		return view('welcome')->with(compact('topjobs', 'topcompanies'));
    	}
    	else {
    		$query = DB::table('h1bs')
    				->select(DB::raw('EMPLOYER_NAME, JOB_TITLE, PREVAILING_WAGE, WORKSITE_CITY, CASE_SUBMITTED, EMPLOYMENT_START_DATE, CASE_STATUS, YEAR(CASE_SUBMITTED) as YEAR'))
    				->where('VISA_CLASS', '!=' , 'E-3 Australian');

	    	if($e != 'e') 
	    	{
	    		//$query->whereRaw("MATCH (EMPLOYER_NAME) AGAINST ( '\"$e\"' IN BOOLEAN MODE)");
	    		$query->where('EMPLOYER_NAME','LIKE', $e.'%');

	    		$request->session()->flash('employer', $e);
	    	}
	    	else {
	    		$e = NULL;
	    	}
	    	

	    	if($j != 'j') 
	    	{
	    		//$query->whereRaw("MATCH (JOB_TITLE) AGAINST ( '\"$j\"' IN BOOLEAN MODE)");
	    		$query->where('JOB_TITLE','LIKE', $j.'%');

				$request->session()->flash('job', $j);
	    	}
	    	else {
	    		$j = NULL;
	    	}
	    	

	    	if($c != 'c') 
	    	{
	    		//$query->whereRaw("MATCH (WORKSITE_CITY) AGAINST ( '\"$c\"' IN BOOLEAN MODE)");
	    		$query->where('WORKSITE_CITY','LIKE', $c.'%');
	    		
				$request->session()->flash('city', $c);
	    	}
	    	else {
	    		$c = NULL;
	    	}
	    	

	    	if($y != 'y') 
	    	{
	    		$query->whereYear('DECISION_DATE', $y);

				$request->session()->flash('year', $y);
	    	}


	    	$mydata = $query->get();
	    	
	    	$total_records_count = $mydata->count();
	    	$total_records_avg =$mydata->avg('PREVAILING_WAGE');

	    	$filtered_prog1 = $mydata->where('PREVAILING_WAGE', '<', 100000);
	    	$filtered_prog2 = $mydata->where('PREVAILING_WAGE', '>' ,100001)->where('PREVAILING_WAGE', '<', 150000);
	    	$filtered_prog3 = $mydata->where('PREVAILING_WAGE', '>' ,150001)->where('PREVAILING_WAGE', '<', 200000);
	    	$filtered_prog4 = $mydata->where('PREVAILING_WAGE', '>', 200000);

	    	if ($total_records_count!=0) {
	    		$progress_1_width = ($filtered_prog1->count()*100)/$total_records_count;
		    	$progress_2_width = ($filtered_prog2->count()*100)/$total_records_count;
		    	$progress_3_width = ($filtered_prog3->count()*100)/$total_records_count;
		    	$progress_4_width = ($filtered_prog4->count()*100)/$total_records_count;
	    	} 
	    	else {
	    		$progress_1_width = NULL;
		    	$progress_2_width = NULL;
		    	$progress_3_width = NULL;
		    	$progress_4_width = NULL;
	    	}    	



	    	$grouped_employer = $mydata->groupBy('EMPLOYER_NAME');
	    	$employers = collect();
	    	foreach ($grouped_employer as $key => $value) {
	    		$employer = $key;
	    		$avg = $value->avg('PREVAILING_WAGE');
	    		$count = $value->count();

	    		$data_collection = collect();
	    		$data_collection->put('employer', $employer);
	    		$data_collection->put('average', $avg);
	    		$data_collection->put('count', $count);

	    		$item = new \stdClass();
	    		$item->employer = $employer;
	    		$item->average = $avg;
	    		$item->count = $count;

	    		$employers->push($item);
	    	}
	    	

	    	$grouped_job = $mydata->groupBy('JOB_TITLE');
	    	$jobs = collect();
	    	foreach ($grouped_job as $key => $value) {
	    		$job = $key;
	    		$avg = $value->avg('PREVAILING_WAGE');
	    		$count = $value->count();

	    		$data_collection = collect();
	    		$data_collection->put('job', $job);
	    		$data_collection->put('average', $avg);
	    		$data_collection->put('count', $count);

	    		$item = new \stdClass();
	    		$item->job = $job;
	    		$item->average = $avg;
	    		$item->count = $count;

	    		$jobs->push($item);
	    	}


	    	$grouped_city = $mydata->groupBy('WORKSITE_CITY');
	    	$cities = collect();
	    	foreach ($grouped_city as $key => $value) {
	    		$city = $key;
	    		$avg = $value->avg('PREVAILING_WAGE');
	    		$count = $value->count();

	    		$data_collection = collect();
	    		$data_collection->put('city', $city);
	    		$data_collection->put('average', $avg);
	    		$data_collection->put('count', $count);

	    		$item = new \stdClass();
	    		$item->city = $city;
	    		$item->average = $avg;
	    		$item->count = $count;

	    		$cities->push($item);
	    	}

	    	$grouped_year = $mydata->groupBy('YEAR');
	    	$grouped_year = $grouped_year->sortKeysDesc();
	    	$years = collect();
	    	foreach ($grouped_year as $key => $value) {
	    		$year = $key;
	    		$avg = $value->avg('PREVAILING_WAGE');
	    		$count = $value->count();

	    		$data_collection = collect();
	    		$data_collection->put('year', $year);
	    		$data_collection->put('average', $avg);
	    		$data_collection->put('count', $count);

	    		$item = new \stdClass();
	    		$item->year = $year;
	    		$item->average = $avg;
	    		$item->count = $count;

	    		$years->push($item);
	    	}
	    	
	    	$records = $query->orderBy('CASE_SUBMITTED','desc')->paginate(100);
	    	

	    	return view('welcome', compact('records', 'employers', 'jobs', 'cities', 'years', 'total_records_count', 'total_records_avg', 'topjobs', 'topcompanies', 'e', 'j', 'c', 'y', 'progress_1_width', 'progress_2_width', 'progress_3_width', 'progress_4_width'));
    	}
    }

    public function autocompleteEmployer(Request $request) {
    	$query = $request->get('query','');

        $data = H1b::select('EMPLOYER_NAME as name')
        ->where('VISA_CLASS', '!=', 'E-3 Australian')
        ->where('EMPLOYER_NAME','LIKE', $query. '%')
        ->orderBy('name')
        ->groupBy('name')
        ->limit(50)
        ->get();

        Log::debug($data);
    	
        return response()->json($data);    	
    }

    public function autocompleteJobTitle(Request $request) {
    	$query = $request->get('query','');
    	
    	$data = H1b::select('JOB_TITLE as name')
    	->where('VISA_CLASS', '!=', 'E-3 Australian')
    	->where('JOB_TITLE','LIKE', $query. '%')
    	->orderBy('name')
    	->groupBy('name')
    	->limit(50)
    	->get();
    	
        return response()->json($data);    	
    }

    public function autocompleteCity(Request $request) {
    	$query = $request->get('query','');
    	
    	$data = H1b::select('WORKSITE_CITY as name')
    	->where('VISA_CLASS', '!=', 'E-3 Australian')
    	->where('WORKSITE_CITY','LIKE', $query. ' %')
    	->orderBy('name')
    	->groupBy('name')
    	->limit(50)
    	->get();

        return response()->json($data);
    }
}
