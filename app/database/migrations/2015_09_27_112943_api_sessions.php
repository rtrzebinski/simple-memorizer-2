<?php

use Illuminate\Database\Migrations\Migration;

class ApiSessions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('api_sessions', function ($table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('auth_token');
			$table->index('auth_token');
			$table->string('client_name');
			$table->string('client_ip');
			$table->timestamp('created_at')->nullable()->default(null);
			$table->timestamp('updated_at')->nullable()->default(null);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('api_sessions');
	}

}
