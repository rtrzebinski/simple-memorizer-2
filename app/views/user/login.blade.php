@extends('layouts.main')

@section('content')

<div class="container">

	@include ('navbar_guest')

	<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
		@include ('blocks.errors')
		{{ Form::open(['class' => 'form-signin']) }}
		<h3 class="form-signin-heading">Please log in</h3>
		<label for="inputEmail" class="sr-only">Email address</label>
		<input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" value="{{{ $email or '' }}}" required="" autofocus="">
		<br />
		<label for="inputPassword" class="sr-only">Password</label>
		<input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required="">
		<div class="checkbox">
			<label>
				<input type="checkbox" value="remember_me" checked="checked"> Remember me
			</label>
		</div>
		<button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
		{{ Form::close() }}
    </div>

</div>

@stop