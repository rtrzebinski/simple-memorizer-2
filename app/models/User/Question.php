<?php

class User_Question extends \Illuminate\Database\Eloquent\Model {

	public $table = 'user_questions';

	/**
	 * Calculate percent of good answers.
	 * @return int
	 */
	public function percentOfGoodAnswers()
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

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function question()
	{
		return $this->belongsTo('Question', 'question_id');
	}

}
