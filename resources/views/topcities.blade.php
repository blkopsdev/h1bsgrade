@extends('app')

@section('title', config('app.name', 'Laravel'))

@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

<div style="margin-top: 20px; margin-bottom: 60px;">
</div>

<div class="container">
    <div class="page-header mb-5">
        <h5>Cities with Most H-1B Filings</h5>      
    </div>

	<table id="data" class="table table-sm table-bordered table-hover">
		<thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">City Name</th>
                <th scope="col"># of H-1B Filings</th>
                <th scope="col">Average Salary</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($cities as $city)
        		<tr scope="row">
                    <td> {{$loop->index + 1}} </td>
        			<td><a href="{{ route('/', ['e'=> 'e', 'j'=> 'j', 'c'=> str_replace(' ', '-', $city->name), 'y'=>'2018' ]) }}">{{ $city->name }}</a></td>
        			<td>{{ number_format($city->count) }}</td>
        			<td>&#36;{{ number_format($city->average) }}</td>
        		</tr>
        	@endforeach
        </tbody>
	</table>
</div>

@endsection