<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdatesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('updates', function (Blueprint $table) {
			$table->unsignedBigInteger('update_id')->unique();
			$table->json('message')->nullable();
			$table->json('edited_message')->nullable();
			$table->json('channel_post')->nullable();
			$table->json('edited_channel_post')->nullable();
			$table->json('inline_query')->nullable();
			$table->json('chosen_inline_result')->nullable();
			$table->json('callback_query')->nullable();
			$table->json('shipping_query')->nullable();
			$table->json('pre_checkout_query')->nullable();
			$table->json('poll')->nullable();
			$table->json('poll_answer')->nullable();
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
		Schema::dropIfExists('updates');
	}
}
