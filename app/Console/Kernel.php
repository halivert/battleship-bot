<?php

namespace App\Console;

use App\Console\Commands\InitBot;
use App\Console\Commands\LongPollingCommand;
use App\Console\Commands\UpdatesRetryCommand;
use App\Models\Update;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		LongPollingCommand::class,
		UpdatesRetryCommand::class,
		InitBot::class
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->call(function () {
			Update::destroy(
				Update::where('was_processed', true)->pluck('update_id')
			);
		})->daily();
	}
}
