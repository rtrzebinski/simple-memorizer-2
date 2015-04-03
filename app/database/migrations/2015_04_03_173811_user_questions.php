<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserQuestions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_questions', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('question_id')->unsigned();
			$table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->integer('number_of_good_answers')->default(0);
			$table->integer('number_of_bad_answers')->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_questions', function() {
			Schema::drop('user_questions');
		});
	}

}
