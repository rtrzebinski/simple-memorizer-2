@extends('layouts.main')

@section('content')
<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
	@include ('navbar')
	<h3>this is OVERVIEW page</h3>
	<h3>Hello, {{{ $user->name }}}</h3>
	<h3><a href="{{{ route('logout') }}}">logout</a></h3>
	<h3><a href="{{{ route('questions') }}}">questions</a></h3>
	<h3><a href="{{{ route('learning_page') }}}">learning page</a></h3>
</div>
@stop