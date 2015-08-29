<nav class="navbar navbar-default navbar-static-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#">Simple memorizer 2</a>
		</div>
		<ul class="nav navbar-nav">
			<li @if(Route::current()->getName() == 'overview') class='active' @endif><a href="{{ route('overview') }}">Overview</a></li>
			<li @if(Route::current()->getName() == 'questions') class='active' @endif><a href="{{ route('questions') }}">Questions</a></li>
			<li @if(Route::current()->getName() == 'learning_page') class='active' @endif><a href="{{ route('learning_page') }}">Learn</a></li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="{{ route('logout') }}">Logout</a></li>
		</ul>
	</div>
</nav>