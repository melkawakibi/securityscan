<!DOCTYPE html>
<html>
<head>
	<title>Admin Panel</title>		
	{!! Html::style('css/style.css') !!}
	{!! Html::script('js/app.js') !!}
	{!! Html::style('jquery-ui-1.12.1/jquery-ui.min.css') !!}
	{!! Html::script('jquery-ui-1.12.1/jquery-ui.min.js') !!}
	{!! Html::script('js/script.js') !!}
</head>
<body>

<h1>Admin Panel</h1>

<table>
<tr>
	<th>id</th>
	<th>naam</th>
	<th>bedrijf</th>
	<th>url</th>
	<th>email</th>
	<th>tweede email</th>
	<th>active</th>
</tr>
@foreach ($customers as $customer)
<tr>
	<td>{{ $customer->id }}</td>
	<td>{{ $customer->name }}</td>
	<td>{{ $customer->company }}</td>
	<td>{{ $customer->cms_url }}</td>
	<td>{{ $customer->cms_email }}</td>
	<td>{{ $customer->second_email }}</td>
	<td>
		<input type="button" id="active" name="active" value="{{ $customer->active ? 'Active' : 'Inactive' }}">

		<p hidden>{{ $customer->id }}</p>
	</td>
</tr>

<p id="error"></p>

@endforeach
</table>

<div id="dialog-confirm" title="Account beheer">
  <p id="dialogtext"></p>
</div>

</body>
</html>