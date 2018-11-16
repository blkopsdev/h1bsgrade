@extends('app')

@if (isset($e))
	@section('title',  ucwords(strtolower($e)) .' '. ucwords(strtolower($j)) .' '. ucwords(strtolower($c)) .' - Salary for H1B Visa, Sponsor Statistics ')
@else
	@section('title', 'H1B Visa Sponsors Database,  Salary Info, Company Data - 2019')
@endif

@section('content')

<!-- Styles -->
<link href="{{ asset('css/typeaheadjs.css') }}" rel="stylesheet">
<script src="{{ asset('js/bloodhound.js') }}"></script>
<script src="{{ asset('js/typeahead.jquery.js') }}"></script>
<script src="{{ asset('js/jquery.tablesorter.js') }}"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

<div class="text-center text-muted" style="margin-top: 20px; margin-bottom: 120px;">
  <h3 style="color:#f16334;">Search H1B Visa Sponsors & Salary </h3>
</div>

<style type="text/css">

	table.tablesorter thead tr .header {
		background-image: url({{ URL::asset('img/bg.gif') }});
		background-repeat: no-repeat;
		background-position: center right;
		cursor: pointer;
	}

	table.tablesorter thead tr .headerSortUp {
		background-image: url({{ URL::asset('img/asc.gif') }});
		background-color: #3399FF;
	}
	table.tablesorter thead tr .headerSortDown {
		background-image: url({{ URL::asset('img/desc.gif') }});
		background-color: #3399FF;
	}

	.count {
		background: red;
	}

	p {
	    margin: 0;
	    padding: 0;
	}

	#median {
		min-width: 150px;
		text-align: center;
	}

	#count {
		min-width: 50px;
		text-align: center;
	}

	.large-dropdown {
		width: 750px;
	    transform: translate3d(5px, 35px, 0px)!important;
	}

</style>


<div class="container">
	<div class="row justify-content-center">
		Trending now:  
		<span class="mx-1"><a href="{{route('/', ['e'=> 'Facebook', 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ])}}">Facebook,</a></span> 
		<span class="mx-1"><a href="{{ route('/', ['e'=> 'Amazon', 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ]) }}">Amazon,</a></span> 
		<span class="mx-1"><a href="{{ route('/', ['e'=> 'Apple', 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ]) }}" >Apple,</a></span>  
		<span class="mx-1"><a href="{{ route('/', ['e'=> 'Netflix', 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ]) }}" >Netflix,</a></span> 
		<span class="mx-1"><a href="{{ route('/', ['e'=> 'Google', 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ]) }}">Google,</a></span> 
		<span class="mx-1"><a href="{{ route('/', ['e'=> 'Airbnb', 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ]) }}">Airbnb,</a></span> 
		<span class="mx-1"><a href="{{ route('/', ['e'=> 'Uber', 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ]) }}">Uber,</a></span> 
		<span class="mx-1"><a href="{{ route('/', ['e'=> 'Linkedin', 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ]) }}">Linkedin,</a></span> 
		<span class="mx-1"><a href="{{ route('/', ['e'=> 'Salesforce', 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ]) }}">Salesforce,</a></span> 
	</div>

	<form method="POST" action="{{ route('formsubmit') }}" style="margin-bottom: 50px;">
		@csrf
		<div class="form-row justify-content-md-center form-group">
			<div class="col-12 col-md-3 my-1">
				<input id="employer" name="employer" value="{{ isset($e) ? $e : '' }}" type="text" class="typeahead form-control" placeholder="Employer">
			</div>

			<div class="col-12 col-md-3 my-1">
				<input id="job" name="job" value="{{ isset($j) ? $j : '' }}" type="text" class="typeahead form-control" placeholder="and/or Job Title">
			</div>

			<div class="col-12 col-md-3 my-1">
				<input id="city" name="city" value="{{ isset($c) ? $c : '' }}" type="text" class="typeahead form-control" placeholder="and/or City">
			</div>

			<div class="col-12 col-md-2 my-1">
				<select class="form-control" id="year" name="year">
					<option value="All Years" {{ Session::get('year') == 'All Years' ? 'selected':'' }}>All Years</option>
					<option value="2018" {{ Session::get('year') == 2018 ? 'selected':'' }}>2018</option>
					<option value="2017" {{ Session::get('year') == 2017 ? 'selected':'' }}>2017</option>
					<option value="2016" {{ Session::get('year') == 2016 ? 'selected':'' }}>2016</option>
					<option value="2015" {{ Session::get('year') == 2015 ? 'selected':'' }}>2015</option>
					<option value="2014" {{ Session::get('year') == 2014 ? 'selected':'' }}>2014</option>
					<option value="2013" {{ Session::get('year') == 2013 ? 'selected':'' }}>2013</option>
					<option value="2012" {{ Session::get('year') == 2012 ? 'selected':'' }}>2012</option>
	    		</select>
			</div>
		</div>

		<div class="row justify-content-center">
			<button type="submit" class="btn" style="width: 200px;color:white;background:#f16334 ">Search</button>
		</div>
	</form>



	<div class="row justify-content-center mb-3">
		@if (isset($e))
			<h6><span class="text-light bg-info border rounded px-1">New</span> <a href="{{ route('listlca', ['company'=> str_replace(' ', '-', Session::get('employer'))]) }}"> <b>Click to see up to date H1-B filings from <u>{{$e}}</u></b></a></h6>
		@endif
	</div>

	@if(isset($records) && count($records) > 0)
		<p class="text-muted mb-3">{{$total_records_count}} Records, Median Salary &#36;{{number_format($total_records_avg)}}</p>

		<div class="row container justify-content-start">

			<div class="mr-1 mb-1">
				<div class="dropdown">
					<button class="btn btn-light border dropdown-toggle" type="button" id="dropdownEmployer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Employer ({{ count($employers) }})
					</button>

					<div class="large-dropdown dropdown-menu" aria-labelledby="dropdownEmployer">
						@foreach ($employers as $employer)
							<a class="dropdown-item py-2" href="{{ route('/', ['e'=> str_replace(' ', '-', $employer->employer), 'j'=>Session::has('job') ? str_replace(' ', '-', Session::get('job')) : 'j', 'c'=>Session::has('city') ? str_replace(' ', '-', Session::get('city')) : 'c', 'y'=>Session::has('year') ? str_replace(' ', '-', Session::get('year')) : 'y' ]) }}" style="display: flex">
								<span style="flex-grow: 1">{{$employer->employer}}</span>
								
								@if ($employer->average < 100000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-primary border rounded">Median &#36;{{number_format($employer->average)}}</span>
								@elseif ($employer->average > 100000 && $employer->average < 150000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-success border rounded">Median &#36;{{number_format($employer->average)}}</span>
								@elseif ($employer->average > 150000 && $employer->average < 200000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-warning border rounded">Median &#36;{{number_format($employer->average)}}</span>
								@else
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-danger border rounded">Median &#36;{{number_format($employer->average)}}</span>
								@endif

								
								<span id="count" class="px-2 ml-1 font-weight-bold text-white bg-secondary border border-secondary rounded">{{$employer->count}}</span>
								
							</a>						    
						@endforeach
					</div>
				</div>
			</div>

			<div class="mr-1 mb-1">
				<div class="dropdown">
					<a class="btn btn btn-light border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Job Titles ({{ count($jobs) }})
					</a>

					<div class="large-dropdown dropdown-menu" aria-labelledby="dropdownMenuLink">
						@foreach ($jobs as $job)
							<a class="dropdown-item  py-2" href="{{ route('/', ['e'=> Session::has('employer') ? str_replace(' ', '-', Session::get('employer')) : 'e', 'j'=> str_replace(' ', '-', $job->job), 'c'=>Session::has('city') ? str_replace(' ', '-', Session::get('city')) : 'c', 'y'=>Session::has('year') ? str_replace(' ', '-', Session::get('year')) : 'y' ]) }}" style="display: flex">
								<span style="flex-grow: 1">{{$job->job}}</span>
								
								@if ($job->average < 100000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-primary border rounded">Median &#36;{{number_format($job->average)}}</span>
								@elseif ($job->average > 100000 && $job->average < 150000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-success border rounded">Median &#36;{{number_format($job->average)}}</span>
								@elseif ($job->average > 150000 && $job->average < 200000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-warning border rounded">Median &#36;{{number_format($job->average)}}</span>
								@else
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-danger border rounded">Median &#36;{{number_format($job->average)}}</span>
								@endif								


								<span id="count" class="px-2 ml-1 font-weight-bold text-white bg-secondary border border-secondary rounded">{{$job->count}}</span>
							</a>						    
						@endforeach
					</div>
				</div>
			</div>

			<div class="mr-1 mb-1">
				<div class="dropdown">
					<a class="btn btn-light border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Cities ({{ count($cities) }})
					</a>

					<div class="large-dropdown dropdown-menu" aria-labelledby="dropdownMenuLink">
						@foreach ($cities as $city)
							<a class="dropdown-item py-2" href="{{ route('/', ['e'=> Session::has('employer') ? str_replace(' ', '-', Session::get('employer')) : 'e', 'j'=>Session::has('job') ? str_replace(' ', '-', Session::get('job')) : 'j', 'c'=> str_replace(' ', '-', $city->city), 'y'=>Session::has('year') ? str_replace(' ', '-', Session::get('year')) : 'y' ]) }}" style="display: flex">
								<span style="flex-grow: 1">{{$city->city}}</span>
								
								@if ($city->average < 100000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-primary border rounded">Median &#36;{{number_format($city->average)}}</span>
								@elseif ($city->average > 100000 && $city->average < 150000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-success border rounded">Median &#36;{{number_format($city->average)}}</span>
								@elseif ($city->average > 150000 && $city->average < 200000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-warning border rounded">Median &#36;{{number_format($city->average)}}</span>
								@else
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-danger border rounded">Median &#36;{{number_format($city->average)}}</span>
								@endif
								
								<span id="count" class="px-2 ml-1 font-weight-bold text-white bg-secondary border border-secondary rounded">{{$city->count}}</span>
							</a>						    
						@endforeach
					</div>
				</div>
			</div>

			<div class="mr-1 mb-1">
				<div class="dropdown">
					<a class="btn btn-light border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Years ({{ count($years) }})
					</a>

					
					<div class="large-dropdown dropdown-menu" aria-labelledby="dropdownMenuLink">
						@foreach ($years as $year)
							<a class="dropdown-item py-2" href="{{ route('/', ['e'=> Session::has('employer') ? str_replace(' ', '-', Session::get('employer')) : 'e', 'j'=>Session::has('job') ? str_replace(' ', '-', Session::get('job')) : 'j', 'c'=> Session::has('city') ? str_replace(' ', '-', Session::get('city')) : 'c', 'y'=> str_replace(' ', '-', $year->year) ]) }}" style="display: flex">
								<span style="flex-grow: 1">{{$year->year}}</span>
															
								@if ($year->average < 100000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-primary border rounded">Median &#36;{{number_format($year->average)}}</span>
								@elseif ($year->average > 100000 && $year->average < 150000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-success border rounded">Median &#36;{{number_format($year->average)}}</span>
								@elseif ($year->average > 150000 && $year->average < 200000)
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-warning border rounded">Median &#36;{{number_format($year->average)}}</span>
								@else
									<span id="median" class="px-2 ml-1 font-weight-bold text-white bg-danger border rounded">Median &#36;{{number_format($year->average)}}</span>
								@endif

								<span id="count" class="px-2 ml-1 font-weight-bold text-white bg-secondary border border-secondary rounded">{{$year->count}}</span>
							</a>
						@endforeach
					</div>
				</div>
			</div>			

		</div>



		<div class="progress mt-4">
			<div class="progress-bar bg-info" role="progressbar" style='width:{{$progress_1_width}}%' aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">&lt;100k</div>

			<div class="progress-bar bg-success" role="progressbar" style="width:{{$progress_2_width}}%" aria-valuemin="0" aria-valuemax="100">100k-150k</div>

			<div class="progress-bar bg-warning" role="progressbar" style="width:{{$progress_3_width}}%" aria-valuemin="0" aria-valuemax="100">150k-200k</div>

			<div class="progress-bar bg-danger" role="progressbar" style="width:{{$progress_4_width}}%" aria-valuemin="0" aria-valuemax="100">&gt;200k</div>
		</div>

		
		<table id="data" class="table table-sm table-bordered table-hover tablesorter mt-4">
			<thead class="thead-light">
                <tr>
                    <th scope="col">EMPLOYER</th>
                    <th scope="col">JOB TITLE</th>
                    <th scope="col">BASE SALARY</th>
                    <th scope="col">LOCATION</th>
                    <th scope="col">SUBMIT DATE</th>
                    <th scope="col">START DATE</th>
                    <th scope="col">CASE STATUS</th>
                </tr>
            </thead>
            <tbody>
            	@foreach($records as $record)
            		<tr scope="row">
            			<td>
            				<a href="{{ route('/', ['e'=> str_replace(' ', '-', $record->EMPLOYER_NAME), 'j'=>Session::has('job') ? str_replace(' ', '-', Session::get('job')) : 'j', 'c'=>Session::has('city') ? str_replace(' ', '-', Session::get('city')) : 'c', 'y'=>Session::has('year') ? str_replace(' ', '-', Session::get('year')) : 'y' ]) }}">{{ $record->EMPLOYER_NAME }}</a>
            			</td>
            			
            			<td><a href="{{ route('/', ['e'=> Session::has('employer') ? str_replace(' ', '-', Session::get('employer')) : 'e', 'j'=> str_replace(' ', '-', $record->JOB_TITLE), 'c'=>Session::has('city') ? str_replace(' ', '-', Session::get('city')) : 'c', 'y'=>Session::has('year') ? str_replace(' ', '-', Session::get('year')) : 'y' ]) }}">{{ $record->JOB_TITLE }}</a></td>

            			<td>{{ number_format($record->PREVAILING_WAGE) }}</td>

            			<td><a href="{{ route('/', ['e'=> Session::has('employer') ? str_replace(' ', '-', Session::get('employer')) : 'e', 'j'=>Session::has('job') ? str_replace(' ', '-', Session::get('job')) : 'j', 'c'=> str_replace(' ', '-', $record->WORKSITE_CITY), 'y'=>Session::has('year') ? str_replace(' ', '-', Session::get('year')) : 'y' ]) }}">{{ $record->WORKSITE_CITY }}</a></td>

            			<td>{{\Carbon\Carbon::parse($record->CASE_SUBMITTED)->format('m/d/Y')}}</td>
            			<td>{{\Carbon\Carbon::parse($record->EMPLOYMENT_START_DATE)->format('m/d/Y')}}</td>
            			<td>{{ $record->CASE_STATUS }}</td>
            		</tr>
            	@endforeach
            </tbody>
		</table>
		{{ $records->links() }}
	@else
		<!-- to add text in the bottom of the button regarding LCA
			<div class="row justify-content-center mb-3">
				<p>This website indexed Labor Condition Application ("<a href="http://en.wikipedia.org/wiki/Labor_Condition_Application">LCA</a>") disclosure data from <a href="http://www.foreignlaborcert.doleta.gov/performancedata.cfm#dis">UNITED STATES DEPARTMENT OF LABOR.</a></p>
				<p>Prior to filing the H-1B petition with the USCIS, an employer must file a LCA with the Department of Labor.</p>
				<p>A Labor Condition Application ("LCA") is used by employers as supporting evidence for the petition for an H-1B visa.</p>
				<p>DOL disclosure data does not indicate the employer's intended use for the LCA.</p>
			</div>
	    -->
	@endif
</div>

<script src="{{ asset('js/autocomplete.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
        $("#data").tablesorter(); 
    }); 
</script>

@endsection