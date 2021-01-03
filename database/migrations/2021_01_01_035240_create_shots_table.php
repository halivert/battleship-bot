<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShotsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shots', function (Blueprint $table) {
			$table->id();
			$table->foreignUuid('game_id')->constrained();
			$table->char('board');
			$table->boolean('was_successful')->default(false);
			$table->json('coordinates');
			$table->json('status');

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
		Schema::dropIfExists('shots');
	}
}
