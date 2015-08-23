<?php

/**
 * User question eloquent model
 * 
 * Connects User with Question
 */
class UserQuestion extends \Illuminate\Database\Eloquent\Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	public $table = 'user_questions';

	/**
	 * User relation
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	/**
	 * Question relation
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function question()
	{
		return $this->belongsTo('Question', 'question_id');
	}

	/**
	 * Update number of answers
	 * 
	 * Increase number of good or bad answers in both DB and object
	 * Calculate percent of good answers
	 * 
	 * @param bool $isAnswerCorrect
	 * true = number of good answers increased
	 * false = number of bad answers increased
	 */
	public function updateAnswers($isAnswerCorrect)
	{
		DB::transaction(function() use ($isAnswerCorrect) {
			$this->increaseField($isAnswerCorrect ? 'number_of_good_answers' : 'number_of_bad_answers');
			$this->updatePercentOfGoodAnswers();
		});
	}

	/**
	 * Increase provided field by 1, and update object state
	 * @param string $field DB column name
	 */
	private function increaseField($field)
	{
		DB::table($this->table)->
			where('id', $this->id)->
			update([
				$field => DB::raw("$field + 1")
		]);
		$this->$field = DB::table($this->table)->where('id', $this->id)->pluck($field);
	}

	/**
	 * Recalculate percent_of_good_answers, and update db field
	 */
	private function updatePercentOfGoodAnswers()
	{
		$this->percent_of_good_answers = $this->calculatePercentOfGoodAnswers();
		DB::table($this->table)->
			where('id', $this->id)->
			update([
				'percent_of_good_answers' => $this->percent_of_good_answers
		]);
	}

	/**
	 * Calculate percent of good answers.
	 * Uses number_of_good_answers and number_of_bad_answers currently set in object.
	 * @return int
	 */
	private function calculatePercentOfGoodAnswers()
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

}
