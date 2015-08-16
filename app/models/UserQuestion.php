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

	/**
	 * Increase number of good answer in both DB and object
	 */
	public function increaseNumberOfGoodAnswers()
	{
		DB::transaction(function() {
			$this->increaseField('number_of_good_answers');
			$this->updatePercentOfGoodAnswers();
		});
	}

	/**
	 * Increase number of bad answer in both DB and object
	 */
	public function increaseNumberOfBadAnswers()
	{
		DB::transaction(function() {
			$this->increaseField('number_of_bad_answers');
			$this->updatePercentOfGoodAnswers();
		});
	}

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

}
