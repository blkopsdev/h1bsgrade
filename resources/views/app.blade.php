<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style type="text/css">
    	html {
		  position: relative;
		  min-height: 100%;
		}
		body {
			padding-bottom: 60px;
		  margin-bottom: 60px;
		}

		p {
			margin: 0;
			padding: 0;
		}

		.footer {
		  position: absolute;
		  bottom: 0;
		  width: 100%;
		  height: 60px;
		  line-height: 60px;
		  background-color:#fff;
		  -webkit-box-shadow:0 -2px 4px rgba(0,0,0,.04);
		  box-shadow:0 -2px 4px rgba(0,0,0,.04)
		}

		.footer > .container {
			padding-top: 30px;
			padding-bottom: 50px;
		    padding-right: 15px;
		    padding-left: 15px;
		    text-align: center;
		}
    </style>
</head>
<body>
    <div id="app">
		<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
			<div class='container'> 
				<a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">	

						<li class="nav-item dropdown px-2">
							<a class="nav-link dropdown-toggle" href="#" id="companiesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Companies
							</a>
							<div class="dropdown-menu" aria-labelledby="companiesDropdown">
								@foreach ($topcompanies as $company)
									<a class="dropdown-item" href="{{ route('/', ['e'=> str_replace(' ', '-', $company->name), 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ]) }}">{{title_case($company->name)}}</a>
								@endforeach
								<a class="dropdown-item" href="{{route('topcompanies')}}">More...</a>
							</div>
						</li>

						<li class="nav-item dropdown px-2">
							<a class="nav-link dropdown-toggle" href="#" id="jobTitlesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							  Job Titles
							</a>
							<div class="dropdown-menu" aria-labelledby="jobTitlesDropdown">
								@foreach ($topjobs as $job)
									<a class="dropdown-item" href="{{ route('/', ['e'=> 'e', 'j'=> str_replace(' ', '-', $job->name), 'c'=> 'c', 'y'=>'2018' ]) }}">{{title_case($job->name)}}</a>
								@endforeach
								<a class="dropdown-item" href="{{route('topjobs')}}">More...</a>
							</div>
						</li>					

						<li class="nav-item px-2">
							<a class="nav-link" href="{{route('topcities')}}">Cities</a>
						</li>

						<li class="nav-item dropdown px-2">
							<a class="nav-link dropdown-toggle" href="#" id="highestPaidDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Highest Paid
							</a>
							<div class="dropdown-menu" aria-labelledby="highestPaidDropdown">
								<a class="dropdown-item" href="{{ route('highestpaidcompany')}}">Companies</a>
								<a class="dropdown-item" href="{{ route('highestpaidjob')}}">Jobs</a>
								<a class="dropdown-item" href="{{ route('highestpaidcity')}}">Cities</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <footer class="footer">
      <div class="container">
      	<h6 class="text-muted">Indexed about 4 million records between 2012 and 2018 of Labor Condition Application (LCA) 
      		<p>disclosure data used for H1B Visa Filing from <a href="http://www.foreignlaborcert.doleta.gov/performancedata.cfm#dis">US Dept of Labor.</a></p> </h6>
        <h6 class="text-muted">Copyright @ h1bgrader.com.  All rights reserved</h6>
	    <h6><a href="mailto:support@h1bgrader.com">Contact</a></h6>
      </div>
    </footer>
</body>
</html>
