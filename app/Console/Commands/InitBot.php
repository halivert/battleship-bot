<?php

namespace App\Console\Commands;

use App\Models\ErrorResponse;
use App\Traits\HasWebhook;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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

		$this->registerRoutes($bot);
	}

	public function registerRoutes($bot)
	{
		$routeDir = base_path() . '/routes';
		$fileContents = file_get_contents("$routeDir/bot.stub");

		$replace = [
			'{{ botToken }}' => $bot->token,
			'{{ botClass }}' => get_class($bot)
		];

		foreach ($replace as $key => $value) {
			$fileContents = str_replace($key, $value, $fileContents);
		}

		$routeFile = "$routeDir/bots";
		if (!file_exists($routeFile) or !is_dir($routeFile)) {
			try {
				if (file_exists($routeFile)) {
					rename($routeFile, "$routeDir/bots.tmp");
				}

				mkdir($routeFile);
			} catch (\Exception $e) {
				return $this->error($e->getMessage());
			}
		}

		$routeFile .= "/" . Str::of(class_basename(get_class($bot)))->lower();

		if (!file_put_contents("$routeFile.php", $fileContents)) {
			return $this->error("Failure creating $routeFile.php");
		}

		$this->info("$routeFile.php overwritten");
	}
}
