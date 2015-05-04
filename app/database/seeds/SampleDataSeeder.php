<?php

class SampleDataSeeder extends Seeder {

	public function run()
	{
		$user = User::create([
				'name' => 'test user 1',
				'email' => 'foo@bar.com',
				'password' => Hash::make('password')
		]);
		$question1 = Question::create([
				'question' => '1 + 1',
				'answer' => '2'
		]);
		$question2 = Question::create([
				'question' => '2 + 2',
				'answer' => '4'
		]);
		$question3 = Question::create([
				'question' => '3 + 3',
				'answer' => '6'
		]);
		$question4 = Question::create([
				'question' => '4 + 4',
				'answer' => '8'
		]);
		$question5 = Question::create([
				'question' => '5 + 5',
				'answer' => '10'
		]);
		User_Question::create([
			'user_id' => $user->id,
			'question_id' => $question1->id
		]);
		User_Question::create([
			'user_id' => $user->id,
			'question_id' => $question2->id
		]);
		User_Question::create([
			'user_id' => $user->id,
			'question_id' => $question3->id
		]);
		User_Question::create([
			'user_id' => $user->id,
			'question_id' => $question4->id
		]);
		User_Question::create([
			'user_id' => $user->id,
			'question_id' => $question5->id
		]);
	}

}
