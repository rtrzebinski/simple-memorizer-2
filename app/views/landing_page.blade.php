@extends('layouts.main')

@section('content')
	<h3>this is LANDING page</h3>
	<h3><a href="{{{ route('login') }}}">login</a></h3>
	<h3><a href="{{{ route('signup') }}}">signup</a></h3>
@stop