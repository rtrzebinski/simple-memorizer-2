<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsers extends Migration {

	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up() {
		Schema::create('users', function(Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->string('name', 128)->nullable();
			$table->string('email', 256);
			$table->unique('email');
			$table->string('password', 256);
			// remember token is nullable by default
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down() {
		Schema::table('users', function() {
			Schema::drop('users');
		});
	}

}
