<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class SetWebhookCommand extends Command
{
	protected $signature = 'setwebhook {--y|yes}';

	protected $description = 'Set webhook in telegram bot';

	protected $botId;

	public function __construct()
	{
		parent::__construct();

		$this->botId = env('BOT_TOKEN');
		if (!$this->botId) {
			$this->error('Bot token not found');
		}
	}

	public function handle()
	{
		$client = new Client();

		$res = $client->request(
			'GET',
			"https://api.telegram.org/bot$this->botId/getWebhookInfo"
		);

		if ($res->getStatusCode() === 200) {
			$response = json_decode($res->getBody(), true);

			$url = Arr::get($response, 'result.url');

			$this->setWebhook(!!$url);
		} else {
			$this->error($res->getStatusCode());
		}
	}

	protected function setWebhook(bool $setted = true)
	{
		$message = 'Webhook not set, do you want to set a webhook?';

		if ($setted) {
			$message = 'Webhook setted, do you want to replace webhook?';
		}

		$yes = $this->option('yes');

		if (!$yes and !$this->confirm($message, true)) {
			return;
		}

		$client = new Client();

		$res = $client->request(
			'GET',
			"https://api.telegram.org/bot$this->botId/setWebhook",
			[
				'query' => [
					'url' => env('APP_URL') . "/bot/$this->botId",
				]
			]
		);

		if ($res->getStatusCode() === 200) {
			$this->info('Webhook setted');
		} else {
			$this->error($res->getBody());
		}
	}
}
