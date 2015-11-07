@extends('layouts.main')

@section('content')
<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
	@include ('navbar')
	<div>
		<h3>Hello, {{{ $user->name }}}</h3>
		<p>
			Go to <a href='{{ route('questions') }}'>questions page</a> to manage your knowledge base, or <a href='{{ route('learning_page_display_user_question') }}'>start learning now</a>.
		</p>
	</div>
</div>
@stop