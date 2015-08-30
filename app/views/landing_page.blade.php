@extends('layouts.main')

@section('content')

<div class="container">

	@include ('navbar_guest')

	<div class="jumbotron">
        <h2>Memorizing does not have to be hard</h2>
        <p class="lead">Simply learn anything you want. Efficiently practice recalling of you knowledge.</p>
        <p><a class="btn btn-lg btn-success" href="{{{ route('signup') }}}" role="button">Sign up for free</a></p>
	</div>

	<div class="row marketing">
        <div class="col-lg-6">
			<h4>It's easier than you think</h4>
			<p>Start in just few minutes, we keep things as simple as possible</p>		

			<h4>The floor is yours</h4>
			<p>Build your own knowledge database, or import an existing one</p>

			<h4>Learn efficiently</h4>
			<p>No time to lose, memorizing by recalling is more effective than just reading</p>
        </div>

        <div class="col-lg-6">
			<h4>It's absolutely free</h4>
			<p>You will never have to pay for using this app</p>

			<h4>Take advantage of every minute</h4>
			<p>Waiting for the bus? Spend few minutes for recalling your knowledge</p>

			<h4>Access everywhere</h4>
			<p>Run on your computer, table or even phone</p>
        </div>
	</div>

</div>

@stop