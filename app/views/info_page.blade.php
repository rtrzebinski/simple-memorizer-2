@extends('layouts.main')

@section('content')

<div class="container col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
	@include ('navbar')
	<div class="alert alert-info" role="alert">{{ $info }}</div>
</div>

@stop