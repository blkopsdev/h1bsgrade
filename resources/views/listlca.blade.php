@extends('app')

@section('title', config('app.name', 'Laravel'))

@section('content')

<!-- load jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<!-- provide the csrf token -->
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

<div style="margin-top: 20px; margin-bottom: 120px;">
</div>

<style type="text/css">
	p {
	    margin: 0;
	    padding: 0;
	}
</style>

<div class="container">
    <div class="page-header mb-5">
        <h5><a href="{{ route('/', ['e'=> str_replace(' ', '-', $company), 'j'=> 'j', 'c'=> 'c', 'y'=>'y' ]) }}">{{$company}}</a> Latest H-1B Filings</h5>      
    </div>

	<table id="data" class="table table-sm table-bordered table-hover">
		<thead class="thead-light">
            <tr>
                <th scope="col">Index</th>
                <th scope="col">Employer</th>
                <th scope="col">Post Date</th>
                <th scope="col">Job Title</th>
                <th scope="col">Salary</th>
                <th scope="col">State</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($listlca as $lca)
        		<tr scope="row">
                    <td>{{$loop->index + 1}}</td>
        			<td>{{ $lca->EMPLOYER_NAME }}</td>
        			<td>{{\Carbon\Carbon::parse($lca->CASE_SUBMITTED)->format('m/d/Y')}}</td>
        			<td><a href="{{ route('listlca', ['company'=> str_replace(' ', '-', $lca->EMPLOYER_NAME), 'job' => str_replace(' ', '-', $lca->JOB_TITLE)]) }}">{{ $lca->JOB_TITLE }}</a></td>
                    
                    @if (Session::get($company)->contains($lca->id))
                        <td><a href="javascript:void(0)">&#36;{{ number_format($lca->PREVAILING_WAGE) }}</a></td>
                    @else
                        <input type="hidden"  value="{{$lca->id}}"  class="project_id">
                        <td><a href="javascript:void(0)" data-company-id="{{$lca->id}}" data-company-name="{{$company}}" class="lca">Click to Reveal</a></td>
                    @endif
                    
                    <td>{{ $lca->WORKSITE_STATE }}</td>
        		</tr>
        	@endforeach
        </tbody>
	</table>
    {{ $listlca->links() }}

    <script type="text/javascript">

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $(".lca").click(function(){
            var id = $(this).data('company-id');
            var company = $(this).data('company-name');
            console.log(id);
            $.ajax({
                type:'POST',
                url:'/clickReveal',
                data: {'_token': CSRF_TOKEN, 'id': id, 'company': company},
                success:function(data){
                    location.reload();
                }
            });
        });

    </script>
	
</div>

@endsection