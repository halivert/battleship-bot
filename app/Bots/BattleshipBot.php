<?php

namespace App\Bots;

use App\Models\Bot;
use App\Models\Message;
use App\Models\Update;
use App\Models\User;
use App\Traits\HasWebhook;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class BattleshipBot extends Bot
{
	use HasWebhook;

	protected $botCommands = [
		'inicio' => 'welcome',
		'stats' => 'getStats',
		'bye' => 'bye'
	];

	public function __construct(array $attrs = [])
	{
		parent::__construct($attrs + [
			'token' => env('BOT_TOKEN')
		]);
	}

	public function handleUpdate(Update $update): Update
	{
		if ($update->type === 'message') {
			return $this->handleMessage($update);
		}

		return new Update(['error' => [
			'description' => 'Unknown error'
		]]);
	}

	public function handleMessage(Update $update): Update
	{
		$message = $update->message;

		$lang = optional($message->from)->language_code ?? 'es_MX';

		if ($lang) {
			App::setLocale($lang);
		}

		if (optional($message->entities->first())->type === 'bot_command') {
			$currentCommand = $message->entities->first()->final_value;

			$callback = Arr::get($this->botCommands, $currentCommand);

			if ($callback) {
				if (method_exists($this, $callback)) {
					return $this->$callback($message);
				}

				return new Update([
					'message' => $this->sendMessage([
						'text' => __('Perdón, no conozco ese comando... aún')
					], $message),
				]);
			}

			return new Update([
				'message' => $this->sendMessage([
					'text' => __('Perdón, no conozco ese comando')
				], $message),
			]);
		}

		return new Update([
			'message' => $this->sendMessage([
				'text' => __('Mi no entender 😅'),
			], $message)
		]);
	}

	public function welcome(Message $message): Update
	{
		$user = User::find($message->from->id);
		$messageText = __('Que gusto verte de nuevo por aquí');
		if (!$user) {
			$user = User::create($message->from->toArray());

			$messageText = __('Hola :user 😌', [
				'user' => $user->first_name
			]);
		}

		$message = $this->sendMessage(['text' => $messageText], $message);

		if ($message->has_error) {
			return new Update(['error' => $message->error]);
		}

		return new Update(['message' => $message]);
	}

	public function bye(Message $message): Update
	{
		$user = User::find($message->from->id);
		$messageText = __(
			'Espera, todavía ni me saludas ¿y ya te vas?, bueno... bye'
		);

		if ($user) {
			$messageText = __('Adiós :user, espero verte pronto 👋🏽', [
				'user' => $user->first_name
			]);

			$user->delete();
		}

		$message = $this->sendMessage(['text' => $messageText], $message);

		if ($message->has_error) {
			return new Update(['error' => $message->error]);
		}

		return new Update(['message' => $message]);
	}

	public function getStats(Message $message): Update
	{
		$user = User::find($message->from->id);
		$messageText = __(
			'Todavía no tienes estadísticas 😅' . PHP_EOL
				. 'Por favor escribe /inicio primero'
		);

		if ($user) {
			$messageText = __(
				'No puedo, estoy chiquito (en construcción) 🥺'
			);
		}

		$message = $this->sendMessage(['text' => $messageText], $message);

		if ($message->has_error) {
			return new Update(['error' => $message->error]);
		}

		return new Update(['message' => $message]);
	}
}
