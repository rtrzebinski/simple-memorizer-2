<?php

class User_Question extends \Illuminate\Database\Eloquent\Model {

	public $table = 'user_questions';

	/**
	 * Calculate percent of good answers.
	 * Uses number_of_good_answers and number_of_bad_answers currently set in object.
	 * @return int
	 */
	public function calculatePercentOfGoodAnswers()
	{
		$totalNumberOfAnswers = $this->number_of_good_answers + $this->number_of_bad_answers;
		if ($totalNumberOfAnswers)
		{
			return round(100 * $this->number_of_good_answers / ($totalNumberOfAnswers));
		}
		else
		{
			return 0;
		}
	}

	private function updatePercentOfGoodAnswers()
	{
		$this->percent_of_good_answers = $this->calculatePercentOfGoodAnswers();
		DB::table($this->table)->
			where('id', $this->id)->
			update([
				'percent_of_good_answers' => $this->percent_of_good_answers
		]);
	}

	private function increaseField($field)
	{
		DB::table($this->table)->
			where('id', $this->id)->
			update([
				$field => DB::raw("$field + 1")
		]);
		$this->$field = DB::table($this->table)->where('id', $this->id)->pluck($field);
	}

	public function increaseNumberOfGoodAnswers()
	{
		$this->increaseField('number_of_good_answers');
		$this->updatePercentOfGoodAnswers();
	}

	public function increaseNumberOfBadAnswers()
	{
		$this->increaseField('number_of_bad_answers');
		$this->updatePercentOfGoodAnswers();
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function question()
	{
		return $this->belongsTo('Question', 'question_id');
	}

}
