<!DOCTYPE html>
<html>
<head>
	<title>Report</title>
</head>
<body>

	<h1>Uw rapport is succesvol gegenereerd</h1>

	<ul>
		<li>Bestandnaam: {{ $file }}</li>
		<li>Datum: {{ $report->created_at }}   </li>
	</ul>

	<p>In de bijlage kunt u uw rapport downloaden.</p>
	<p>Voor verdere informatie kunt u contact opnemen met S5 [telefoon]</p>


</body>
</html>