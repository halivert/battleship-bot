<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->unsignedBigInteger('id')->unique();
			$table->boolean('is_bot');
			$table->string('first_name');
			$table->string('last_name')->nullable();
			$table->string('username')->unique()->nullable();
			$table->string('language_code')->nullable();
			$table->boolean('can_join_groups')->nullable();
			$table->boolean('can_read_all_group_messages')->nullable();
			$table->boolean('supports_inline_queries')->nullable();

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
		Schema::dropIfExists('users');
	}
}
