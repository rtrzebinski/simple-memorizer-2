@extends('layouts.main')

@section('content')
	<h3>this is OVERVIEW page</h3>
	<h3>Hello, {{{ $user->name }}}</h3>
	<h3><a href="{{{ route('user_logout') }}}">logout</a></h3>
@stop