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
			<td><b>Bedrijf</b> {{ $customer->company }} </td>
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
			<td><b>Scan Tijd</b> {{ $scan->time_taken }} </td>
		</tr>
	</table>

	<h1>Scan Resultaten</h1>

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

	<table>

		<tr>
			<th>Categorie dreigementen</th>
			<th>Dreigingsniveau</th>
			<th>Aantal</th>
		</tr>

		@foreach($modules as $module)
		<tr>
			<td> {{ $module['module'] }}</td>

			<td> {{ $module['risk'] }}</td>

			<td> {{ $module['count'] }}</td>
		</tr>
		@endforeach

	</table>

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
			<td><b>Parameter: </b> {{$scandetail->parameter}}</td>
		</tr>
		<tr>
			<td><b>Aanval: </b> {{$scandetail->attack}}</td>
		</tr>
		<tr>
			<td><b>Fout: </b> {{$scandetail->error}}</td>
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

</body>
</html>