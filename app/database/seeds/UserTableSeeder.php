<?php

class UserTableSeeder extends Seeder {

	public function run() {
		// delete existing db rows
		DB::table('users')->delete();
		// create new user
		User::create(array(
			'name' => 'test user 1',
			'email' => 'foo@bar.com',
			'password' => Hash::make('password')
		));
	}

}
