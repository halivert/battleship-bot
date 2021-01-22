<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Foundation\Inspiring;

class ExampleConversation extends Conversation
{
	/**
	 * Start the conversation
	 */
	public function run()
	{
		$question = Question::create("¿Que quieres?")
			->fallback('¿Qué? no sé 😅')
			->callbackId('ask_reason')
			->addButtons([
				Button::create('Dime una broma')->value('joke'),
				Button::create('Dime una cosa')->value('quote'),
				Button::create('Whatever')->value('lolo'),
			]);

		return $this->ask($question, fn (Answer $a) => $this->replyAnswer($a));
	}

	public function replyAnswer(Answer $answer)
	{
		if ($answer->isInteractiveMessageReply()) {
			if ($answer->getValue() === 'joke') {
				$joke = json_decode(
					file_get_contents('http://api.icndb.com/jokes/random')
				);
				$this->say($joke->value->joke);
			} else if ($answer->getValue() === 'quote') {
				$this->say(Inspiring::quote());
			}
		}
	}
}
