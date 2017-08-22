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
			<th>id</th>
			<th>module naam</th>
			<th>risico</th>
			<th>parameter</th>
			<th>aanval</th>
			<th>error</th>
			<th>wasc_id</th>
			<th>datum</th>
		</tr>
		@foreach($scans as $scan)
		<tr>
			<td>{{$scan->id}}</td>
			<td>{{$scan->module_name}}</td>
			<td>{{$scan->risk}}</td>
			<td>{{$scan->parameter}}</td>
			<td>{{$scan->attack}}</td>
			<td>{{$scan->error}}</td>
			<td>{{$scan->wasc_id}}</td>
			<td>{{$scan->created_at}}</td>
		</tr>
		@endforeach
	</table>

</body>
</html>