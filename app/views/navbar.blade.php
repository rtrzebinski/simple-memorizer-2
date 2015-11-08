<nav class="navbar navbar-static-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{ route('overview') }}">Simple memorizer 2</a>
		</div>
		<ul class="nav navbar-nav">
			<li><a href="{{ route('display_user_questions') }}">Questions</a></li>
			<li><a href="{{ route('learning_page_display_user_question') }}">Learn</a></li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="{{ route('logout') }}">Logout</a></li>
		</ul>
	</div>
</nav>