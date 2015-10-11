<?php

return [
	'unable_to_login' => [
		'error_code' => '100',
		'error_message' => 'Unable to log in. Bad user credentials.',
	],
	'unable_to_signup' => [
		'error_code' => '101',
		'error_message' => 'Unable to sign up. Not valid user credentials.',
	],
	'bad_auth_token' => [
		'error_code' => '102',
		'error_message' => 'Bad auth token. Use \'login\' or \'signup\' method to obtain auth_token.',
	],
	'user_question_does_not_exist' => [
		'error_code' => '103',
		'error_message' => 'User question does not exist. Ensure provided user question \'id\' is correct.',
	],
	'user_has_not_created_any_questions_yet' => [
		'error_code' => '104',
		'error_message' => 'User has not created any questions yet. Create at least one user question.',
	],
];
