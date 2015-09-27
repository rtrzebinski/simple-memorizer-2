<?php

/**
 * Question eloquent model
 * 
 * Database fields
 * @property int $id Id
 * @property string $question Question text
 * @property string $answer Answer text
 * @property \Carbon\Carbon $created_at Created at
 * @property \Carbon\Carbon $updated_at Updated at
 */
class Question extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	public $table = 'questions';

}
