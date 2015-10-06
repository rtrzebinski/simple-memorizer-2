<?php

/**
 * User repository
 */
class UserRepository {

	/**
	 * Create new user
	 * @param string $email
	 * @param string $password
	 * @return User
	 */
	public function create($email, $password)
	{
		$user = App::make('User');
		$user->email = $email;
		$user->password = Hash::make($password);
		$user->save();

		return $user;
	}

}
