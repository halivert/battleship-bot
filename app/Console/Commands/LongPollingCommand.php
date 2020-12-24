<?php

namespace App\Console\Commands;

use App\Models\Update;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class LongPollingCommand extends Command
{
	protected $signature = 'longpolling';

	protected $description = 'Long polling (only for local development)';

	protected $botId;

	protected $client;

	public function __construct()
	{
		parent::__construct();

		$this->botId = env('BOT_TOKEN');
		$this->client = new Client();

		if (!$this->botId) {
			$this->error('Bot token not found');
		}

		if (!$this->client) {
			$this->error('Error creating client');
		}
	}

	public function handle()
	{
		$apiUrl = env('API_URL');
		while (true) {
			$offset = 0;

			$lastUpdate = Update::orderBy('update_id', 'desc')
				->limit(1)
				->get()
				->first();

			if ($lastUpdate) {
				$offset = $lastUpdate->update_id + 1;
			}

			$response = $this->client->request(
				'GET',
				"$apiUrl/bot$this->botId/getUpdates",
				[
					'query' => [
						'offset' => $offset
					]
				]
			);

			if ($response->getStatusCode() === 200) {
				$update = json_decode($response->getBody(), true);

				if ($update['result']) {
					foreach ($update['result'] as $result) {
						$createdUpdate = Update::create($result);
						$this->info(
							"Update created: $createdUpdate->update_id"
						);
					}
				}
			}

			if ($this->memoryExceeded(120)) {
				return 12; // Memory limit exit
			}
		}
	}

	public function memoryExceeded($memoryLimit): bool
	{
		return (memory_get_usage(true) / 1024 / 1024) >= $memoryLimit;
	}
}
