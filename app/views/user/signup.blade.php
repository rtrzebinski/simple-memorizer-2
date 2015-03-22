@extends('layouts.main')

@section('content')
@include ('blocks.errors')
{{ Form::open() }}
<input type="text" name="email" value="{{{ $email or '' }}}" size="30" /><br/>
<input type="password" name="password" value="" size="30" /><br/>
<input type="submit" value="Sign up" />
{{ Form::close() }}
@stop