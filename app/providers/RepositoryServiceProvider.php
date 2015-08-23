<?php

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->app->bind('UserQuestionRepository', function() {
			// inject currently logged user
			return new UserQuestionRepository(Auth::user());
		});
	}

}
