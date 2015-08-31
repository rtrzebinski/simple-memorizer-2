@extends('layouts.main')

@section('content')

<div class="container col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
	@include ('navbar')
	@include ('blocks.errors')

	<div class="alert alert-info" role="alert">Please select CSV file to import from.</div>
	<div class="alert alert-warning" role="alert">Number of answers of existing rows will be overriden! Select 'Ignore number of answers' to import / override with zeros.</div>

	{{ Form::open(['files' => true]) }}

	<div class="form-group">
		<input type="file" name="csv_file" value="" />
		<p class="help-block">File must be a valid CSV.</p>
	</div>

	<div class="checkbox">
		<label>
			<input type="checkbox" name="reset_number_of_answers" value="ON" /> Ignore number of answers
			<p class="help-block">Select if you don't want to import number of answers from CSV file</p>
		</label>
	</div>

	<button type="submit" class="btn btn-default">Import from CSV file</button>

	{{ Form::close() }}

</div>

@stop