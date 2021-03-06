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
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('email');
			$table->unique('email');
			$table->string('password');
			$table->rememberToken();
			$table->timestamp('created_at')->nullable()->default(null);
			$table->timestamp('updated_at')->nullable()->default(null);
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
