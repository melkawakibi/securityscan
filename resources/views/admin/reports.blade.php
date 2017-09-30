@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                @endif
			</div>

			<table class="table">

	         	<thead>
					<tr>
						<th>klant</th>
						<th>bedrijf</th>
						<th>Type</th>
						<th>Report Type</th>
						<th>Id</th>
						<th>report</th>
						<th>status</th>
						<th>Verzend</th>
					</tr>
				</thead>

				<tbody>
					@foreach ($reports as $report)
					<tr>
						<td>{{ $report['name']  }}</td>
						<td>{{ $report['company'] }}</td>
						<td> {{ $report['scanType'] }} </td>
						<td> {{ $report['reportType'] }} </td>
						<td> {{ $report['id'] }} </td>
						<td> {!! Html::image('images/pdf-icon.png') !!} <a href="{{ $report['report'] }}"> {{ $report['file'] }} </a></td>
						<td> {{ $report['status'] ? 'Verzonden' : 'Niet verzonden' }} </td>
						<td> 

							<input type="button" class="verzend" name="verzend" value="verzenden"> 

							 <p hidden> {{ $report['id'] }} </p>

						</td>
					</tr>

					@endforeach
				</tbody>

			</table>
			<div id="dialog-confirm" title="Account beheer">
				<p id="dialogtext"></p>
			</div>

       	</div>
    </div>
</div>
@endsection