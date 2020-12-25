<?php

namespace App\Console\Commands;

use App\Models\FailedUpdate;
use App\Models\Update;
use Illuminate\Console\Command;

class UpdatesRetryCommand extends Command
{
	protected $signature = 'updates:retry';

	protected $description = 'Retry all updates';

	public function handle()
	{
		$updates = FailedUpdate::all();

		foreach ($updates as $update) {
			Update::create($update->toArray());
		}

		FailedUpdate::destroy($updates->pluck('update_id'));

		$this->info('Retrying... updates in queue again');
	}
}
