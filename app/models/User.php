<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * User eloquent model
 */
class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	/**
	 * User questions relation
	 * @return \Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function userQuestions()
	{
		return $this->hasMany('UserQuestion', 'user_id');
	}

	/**
	 * Random user question
	 * 
	 * Questions that user knows less have more chance to be returned.
	 * Questions that user knows more have less chance to be returned.
	 * 
	 * @return UserQuestion|NULL
	 * NULL will be returned if user has no questions
	 */
	public function randomUserQuestion()
	{
		return App::make('UserQuestionsRandomizer')->randomUserQuestion($this);
	}

}
