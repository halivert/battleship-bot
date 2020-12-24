<?php

namespace App\Observers;

use App\Models\Update;
use App\Models\User;

class UpdateObserver
{
	public function created(Update $update)
	{
		if ($update->type === 'message') {
			$message = $update->message;

			$starting = $message->entities->first()->final_value === '/start';
			if ($starting) {
				$user = User::find($message->from->id);
				$messageText = 'Que gusto verte por aquí';
				if (!$user) {
					User::create($message->from->toArray() + [
						'telegram_id' => $message->from->id
					]);

					$messageText = 'Hola, bienvenido 😌';
				}

				$response = $message->answer([
					'text' => $messageText,
					'parse_mode' => 'html'
				]);

				if ($response->getStatusCode() === 200) {
					$update->delete();
				}
			}
		}
	}
}
