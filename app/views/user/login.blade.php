@extends('layouts.main')

@section('content')
@include ('blocks.errors')
{{ Form::open() }}
<input type="text" name="email" value="{{{ $email or '' }}}" size="30" /><br/>
<input type="password" name="password" value="" size="30" /><br/>
<input type="checkbox" name="remember_me" /> Remember me<br/>
<input type="submit" value="Login" />
{{ Form::close() }}
@stop