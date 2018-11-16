@extends('app')

@section('title', config('app.name', 'Laravel'))

@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

<div style="margin-top: 20px; margin-bottom: 60px;">
</div>

<div class="container">
    <div class="page-header mb-5">
        <h5>Companies with Highest Average Salary <span class="text-muted"><small>(at least 100 fillings)</small></span></h5>      
    </div>

	<table id="data" class="table table-sm table-bordered table-hover">
		<thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Company Name</th>
                <th scope="col"># of H-1B Filings</th>
                <th scope="col">Average Salary</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($highestpaidcompanies as $highestpaidcompany)
        		<tr scope="row">
                    <td> {{$loop->index + 1}} </td>
        			<td><a href="{{ route('/', ['e'=> str_replace(' ', '-', $highestpaidcompany->name), 'j'=> 'j', 'c'=> 'c', 'y'=>'2018' ]) }}">{{ $highestpaidcompany->name }}</a></td>
        			<td>{{ $highestpaidcompany->count }}</td>
        			<td>&#36;{{ number_format($highestpaidcompany->average) }}</td>
        		</tr>
        	@endforeach
        </tbody>
	</table>
</div>

@endsection