<?php

class QuestionsControllerTest extends TestCase {

	public function testListAction()
	{
		$user = $this->createUser();
		$this->be($user);
		$question = $this->createQuestion();
		$question->question = uniqid();
		$question->answer = uniqid();
		$question->save();
		$userQuestion = $this->createUserQuestion($user->id, $question->id);

		$responseContent = $this->route('POST', 'list_questions', [
				'jtSorting' => 'id ASC',
				'jtStartIndex' => '0',
				'jtPageSize' => 1
			])->getContent();

		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
		$this->assertEquals($userQuestion->id, $data->Records[0]->id);
		$this->assertEquals($question->question, $data->Records[0]->question);
		$this->assertEquals($question->answer, $data->Records[0]->answer);
		$this->assertEquals(0, $data->Records[0]->percent_of_good_answers);
	}

	public function testDeleteAction()
	{
		$user = $this->createUser();
		$this->be($user);
		$question = $this->createQuestion();
		$question->question = uniqid();
		$question->answer = uniqid();
		$question->save();
		$userQuestion = $this->createUserQuestion($user->id, $question->id);

		$responseContent = $this->route('POST', 'delete_questions', [
				'id' => $userQuestion->id
			])->getContent();

		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
		$this->assertNull(App::make('User_Question')->find($userQuestion->id));
	}

	public function testUpdateAction()
	{
		$user = $this->createUser();
		$this->be($user);
		$question = $this->createQuestion();
		$question->question = uniqid();
		$question->answer = uniqid();
		$question->save();
		$userQuestion = $this->createUserQuestion($user->id, $question->id);
		$input = [
			'id' => $userQuestion->id,
			'question' => uniqid(),
			'answer' => uniqid()
		];

		$responseContent = $this->route('POST', 'update_questions', $input)->getContent();

		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
		$this->refresh($question);
		$this->assertEquals($input['question'], $question->question);
		$this->assertEquals($input['answer'], $question->answer);
	}

	public function testCreateAction()
	{
		$user = $this->createUser();
		$this->be($user);
		$input = [
			'question' => uniqid(),
			'answer' => uniqid()
		];

		$responseContent = $this->route('POST', 'create_questions', $input)->getContent();

		$data = json_decode($responseContent);
		$this->assertEquals('OK', $data->Result);
		$question = App::make('Question')->
			where('question', $input['question'])->
			where('answer', $input['answer'])->
			first();
		$this->assertEquals($input['question'], $question->question);
		$this->assertEquals($input['answer'], $question->answer);
		$userQuestion = App::make('User_Question')->
			where('question_id', $question->id)->
			first();
		$this->assertEquals($user->id, $userQuestion->user_id);
	}

}
