<?php

namespace App\Console\Commands;

use App\Models\ErrorResponse;
use App\Traits\HasWebhook;
use Illuminate\Console\Command;

class InitBot extends Command
{
	protected $signature = 'init:bot {class}';

	protected $description = 'Initializes bot in server';

	public function handle()
	{
		$class = $this->argument('class');

		$bot = app($class);

		$traits = class_uses_recursive(get_class($bot));

		if (!collect($traits)->contains(HasWebhook::class)) {
			return $this->error('Bot must use HasWebhook trait');
		}

		$newWebhookUrl = env('APP_URL') . "/bot$bot->token";

		if ($bot->webhook_url) {
			if ($bot->webhook_url === $newWebhookUrl) {
				return $this->info("Same webhook url: $bot->webhook_url");
			}

			$resp = $bot->deleteWebhook();

			if ($resp->getStatusCode() !== 200) {
				$error = new ErrorResponse(json_decode($resp->getBody(), true));

				return $this->error($error->description);
			}

			$this->info("Last webhook url: $bot->webhook_url");
		}

		$resp = $bot->setWebhook($newWebhookUrl);

		if ($resp->getStatusCode() !== 200) {
			$error = new ErrorResponse(json_decode($resp->getBody(), true));

			return $this->error($error->description);
		}

		$this->info("New webhook url: $newWebhookUrl");
	}
}
