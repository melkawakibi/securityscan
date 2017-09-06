<!DOCTYPE html>
<html>
<head>
	<title>Report</title>
	<style>
		table{
			border-collapse: collapse;
		}
		td,th{
			border:1px solid;
		}
	</style>
</head>
<body>

	<table>
		<tr>
		 	<th><b>Algemene informatie</b></th>
		</tr>

		@foreach($scan as $data)
		<tr>
			<td><b>Start tijd: </b> $data->created_at</td>
			<td><b>Eind tijd: </b> </td>
		</tr>
		@endforeach
	</table>

	<table>
		<h2>Scan informatie</h2>
		<tr>
			<th>id</th>
			<th>module naam</th>
			<th>risico</th>
			<th>parameter</th>
			<th>aanval</th>
			<th>error</th>
			<th>wasc_id</th>
			<th>datum</th>
		</tr>
		@foreach($scan_details as $scan_detail)
		<tr>
			<td>{{$scan_detail->id}}</td>
			<td>{{$scan_detail->module_name}}</td>
			<td>{{$scan_detail->risk}}</td>
			<td>{{$scan_detail->parameter}}</td>
			<td>{{$scan_detail->attack}}</td>
			<td>{{$scan_detail->error}}</td>
			<td>{{$scan_detail->wasc_id}}</td>
			<td>{{$scan_detail->created_at}}</td>
		</tr>
		@endforeach
	</table>

</body>
</html>