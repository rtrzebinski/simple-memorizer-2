@extends('layouts.admin')

@section('content')
	<h3>this is admin OVERVIEW page</h3>
	<h3>Hello, {{{ $user->name }}}</h3>
	<h3><a href="{{{ action('Admin_UserController@getLogout') }}}">logout</a></h3>
@stop