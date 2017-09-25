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
							<th>Id</th>
							<th>naam</th>
							<th>bedrijf</th>
							<th>url</th>
							<th>email</th>
							<th>tweede email</th>
							<th>active</th>
						</tr>
					</thead>

						<tbody>
							@foreach ($customers as $customer)
							<tr>
								<td>{{ $customer->id }}</td>
								<td>{{ $customer->name }}</td>
								<td>{{ $customer->company }}</td>
								<td>{{ $customer->cms_url }}</td>
								<td>{{ $customer->cms_email }}</td>
								<td>{{ $customer->second_email }}</td>
								<td>
									<input type="button" class="active" name="active" value="{{ $customer->active ? 'Active' : 'Inactive' }}">

									<p hidden>{{ $customer->id }}</p>

								</td>
							</tr>

							<p id="error"></p>
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