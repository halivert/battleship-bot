<?php

namespace App\Console\Commands;

use App\Bots\BattleshipBot;
use App\Models\FailedUpdate;
use App\Models\Update;
use Illuminate\Console\Command;

class LongPollingCommand extends Command
{
	protected $signature = 'longpolling';

	protected $description = 'Long polling (only for local development)';

	public function handle()
	{
		$battleshipBot = new BattleshipBot();

		if ($battleshipBot->webhook_url) {
			$resp = $battleshipBot->deleteWebhook();

			$resp = json_decode($resp->getBody(), true);

			if ($resp['ok'] === true) {
				$this->info('Webhook deleted');
			} else {
				$this->info('Error on webhook');
			}
		}

		$tries = 0;

		while (true) {
			$offset = 0;

			$lastUpdate = Update::orderBy('update_id', 'desc')
				->limit(1)
				->get()
				->first();

			if ($lastUpdate) {
				$offset = $lastUpdate->update_id + 1;
			}

			$updates = $battleshipBot->getUpdates(['offset' => $offset]);

			foreach ($updates as $update) {
				if (!$update->has_error) {
					$update->save();
					$this->info(
						"Update created: $update->update_id"
					);
				}
			}

			$firstUpdate = Update::where('was_processed', false)
				->orderBy('update_id')
				->limit(1)
				->get()
				->first();

			if ($firstUpdate) {
				$update = $battleshipBot->handleUpdate($firstUpdate);
				if ($update->has_error) {
					if ($tries >= 3) {
						$this->error("Failed update: $firstUpdate->update_id");
						FailedUpdate::create($firstUpdate->toArray());
						$firstUpdate->delete();
						$tries = 0;
					} else {
						$this->error($update->getError()->description);
						$tries++;
					}
				} else {
					$this->info("Update processed: $firstUpdate->update_id");
					$firstUpdate->was_processed = true;
					$firstUpdate->save();
					$tries = 0;
				}
			}

			if ($this->memoryExceeded(120)) {
				return 12; // Memory limit exit
			}
		}
	}

	private function memoryExceeded($memoryLimit): bool
	{
		return (memory_get_usage(true) / 1024 / 1024) >= $memoryLimit;
	}
}
