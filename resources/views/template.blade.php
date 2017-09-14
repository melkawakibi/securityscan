<!DOCTYPE html>
<html>
<head>
	<title>Report</title>
	<style>
		table {
		    float: left;
		    clear: left;
		    width: 285px;
		    margin-bottom: 20px;
		    }

		tr, td, th {
		    margin:auto;
		    }

		td, th {
		    padding:5px;
		    vertical-align:top;
		    }

		th {
		    font-weight:bold;
		    background:#ddd;
		    }

		td {
		    border:1px solid #ddd;
		    }
		.thread-level{
			width: 50px;
			height: 30px;
		}
		.level{
			background-color: green;
		}
		.level2{
			background-color: yellow;
		}
		.level3{
			background-color: orange;
		}
		.level4{
			background-color: red;
		}
		.thread{
			width: 50px;
			height: 100px;
		}
	</style>
</head>
<body>

	<h1>Scan Rapport</h1>

	<table>
		<tr>
			<th><b>Algemene Informatie</b></th>
		</tr>
		<tr>
			<td><b>Klant: </b> {{ $customer->name }} </td>
		</tr>
		<tr>
			<td><b>Bedrijf: </b> {{ $customer->company }} </td>
		</tr>
		<tr>
			<td><b>Email: </b> {{ $customer->cms_email }}  </td>
		</tr>
	</table>

	<table>
		<tr>
			<th><b>Scan Informatie</b></th>
		</tr>
		<tr>
			<td><b>URL: </b> {{ $website->base_url }} </td>
		</tr>
		<tr>
			<td><b>Server: </b> {{ $website->server }} </td>
		</tr>
		<tr>
			<td><b>Scan type: </b> {{ $scan->type }} </td>
		</tr>
		<tr>
			<td><b>Start tijd: </b> {{ $scan->created_at }} </td>
		</tr>
		<tr>
			<td><b>Eind tijd: </b> {{ $scan->time_end }} </td>
		</tr>
		<tr>
			<td><b>Scan tijd: </b> {{ $scan->time_taken }} </td>
		</tr>
	</table>

	<h1>Scan Resultaten</h1>

	<table>
		
		<tr>
			<th>Dreigingsniveau (Kleur code)</th>
			<th>Niveau</th>
			<th>Omschrijving</th>
		</tr>

		<tr>
			@if($level === 0)
				<td class="thread-level level"></td>
				<td style="font-size: 20px;text-align: center;">0</td>
				<td>@lang('string.thread_level_0')</td>
			@elseif($level === 1) 
				<td class="thread-level level"></td>
				<td style="font-size: 20px;text-align: center;">1</td>
				<td>@lang('string.thread_level_1')</td>
			@elseif($level === 2)
				<td class="thread-level level2"></td>
				<td style="font-size: 20px;text-align: center;">2</td>
				<td>@lang('string.thread_level_2')</td>
			@elseif($level === 3)
				<td class="thread-level level3"></td>
				<td style="font-size: 20px;text-align: center;">3</td>
				<td>@lang('string.thread_level_3')</td>
			@elseif($level === 4)
				<td class="thread-level level4"></td>
				<td style="font-size: 20px;text-align: center;">4</td>
				<td>@lang('string.thread_level_4')</td>
			@endif
		</tr>

	</table>

	<table>

		<tr>
			<th>Totale dreigementen gevonden</th>
		</tr>
	
		<tr>
			<td><b>Hoog: </b> {{ $risk->high }}</td>
		</tr>
		<tr>
			<td><b>gemiddeld: </b> {{ $risk->average }}</td>
		</tr>
		<tr>
			<td><b>Laag: </b> {{ $risk->low }}</td>
		</tr>

	</table>

	@if($modules->sql['count'] > 0 || $modules->xss['count'] > 0)
	<table>

		<tr>
			<th>Categorie dreigementen</th>
			<th>Dreigingsniveau</th>
			<th>Aantal</th>
			<th>Omschrijving</th>
		</tr>

		@foreach($modules as $module)
		<tr>
			@if($module['count'] > 0)
				<td> {{ $module['module'] }}</td>

				<td> {{ $module['risk'] }}</td>

				<td> {{ $module['count'] }}</td>

				@if($module['module'] === 'BlindSQLi')
					<td>@lang('string.BlindSQLi_description')</td>
				@elseif($module['module'] === 'SQLi')
					<td>@lang('string.SQLi_description')</td>
				@else
					<td>@lang('string.XSS_description')</td>
				@endif
			@endif
		</tr>
		@endforeach

	</table>

	<table>
		@foreach($modules as $module)
			@if($module['count'] > 0 AND $module['module'] !== 'BlindSQLi')					
				<tr>
					@if($module['module'] === 'SQLi')
						<th>Advies: SQLi en Blind SQLi </th>
					@else
						<th>Advies: {{ $module['module'] }} </th>
					@endif
				</tr>

				<tr>
					@if($module['module'] === 'SQLi')
						<td>@lang('string.SQL_advies')</td>
					@else
						<td>@lang('string.XSS_advies')</td>
					@endif
				</tr>
			@endif
		@endforeach

	</table>
	@endif

	@if(!$isShortReport)
		@if(!$isScanDetailEmpty)
		<table>

			<tr>
				<th>Detials van gevonden kwetsbaarheden</th>
			</tr>

			@foreach($scandetails as $scandetail)

			<tr>
				<td><b>ID: </b> {{$scandetail->id}}</td>
			</tr>
			<tr>
				<td><b>Module: </b> {{$scandetail->module_name}}</td>
			</tr>
			<tr>
				<td><b>Risico: </b> {{$scandetail->risk}}</td>
			</tr>
			<tr>
				<td><b>Target URL: </b> {{ $scandetail->target }} </td>
			</tr>
			<tr>
				<td><b>Parameter: </b> {{$scandetail->parameter}}</td>
			</tr>
			<tr>
				<td><b>Aanval: </b> {{$scandetail->attack}}</td>
			</tr>
			<tr>
				<td><b>Error: </b> {{$scandetail->error}}</td>
			</tr>
			<tr>
				<td><b>WASC ID: </b> {{$scandetail->wasc_id}}</td>
			</tr>
			<tr>
				<td><b>Datum: </b> {{$scandetail->created_at}}</td>
			</tr>

			<tr>
				<th></th>
			</tr>
			@endforeach

		</table>
		@endif
	@endif
</body>
</html>