<?php

namespace App\Bots;

use App\Models\Bot;
use App\Models\Message;
use App\Models\Update;
use App\Models\User;
use App\Traits\HasWebhook;

class BattleshipBot extends Bot
{
	use HasWebhook;

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

		if (optional($message->entities->first())->type === 'bot_command') {
			if ($message->entities->first()->final_value === '/start') {
				return new Update(['message' => $this->welcome($message)]);
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

	public function welcome(Message $message): Message
	{
		$user = User::find($message->from->id);
		$messageText = 'Que gusto verte por aquí';
		if (!$user) {
			$user = User::create($message->from->toArray());

			$messageText = __('Hola :user 😌', [
				'user' => $user->first_name
			]);
		}

		$resp = $this->sendMessage([
			'text' => $messageText
		], $message);

		return $resp;
	}
}
