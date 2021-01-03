<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('games', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->foreignId('user_a_id')->constrained('users');
			$table->foreignId('user_b_id')->constrained('users');
			$table->json('board_a');
			$table->json('board_b');

			$table->timestamps(6);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('games');
	}
}
