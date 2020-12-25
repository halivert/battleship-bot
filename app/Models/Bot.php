<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class Bot extends Model
{
	private Client $httpClient;

	private string $apiUrl;

	protected $fillable = [
		'token'
	];

	protected $commands = [];

	public function __construct(array $attrs = [])
	{
		parent::__construct($attrs);

		$this->httpClient = new Client();
		$baseUrl = env('BASE_API_URL');
		$this->apiUrl = "$baseUrl$this->token";
	}

	protected function genericRequest(
		$method,
		array $opts = []
	): ResponseInterface {
		return $this->httpClient->request(
			'GET',
			"$this->apiUrl/$method",
			[
				'http_errors' => false,
				'query' => $opts
			]
		);
	}

	public function handleUpdate(Update $update): Update
	{
		throw new Exception('Not implemented');
	}

	public function getMe(): User
	{
		$resp = $this->genericRequest('getMe');

		if ($resp->getStatusCode() === 200) {
			$rawUser = $resp->getBody();

			if ($rawUser) {
				return new User(json_decode($rawUser, true)['result']);
			}
		}

		return new User(['error' => json_decode($resp->getBody(), true)]);
	}

	public function sendMessage(
		array $opts = [],
		?Message $message = null
	): Message {
		if ($message and !($opts['chat_id'] ?? false)) {
			$opts['chat_id'] = $message->chat->id;
		}

		$resp = $this->genericRequest('sendMessage', $opts);

		if ($resp->getStatusCode() === 200) {
			$rawMessage = $resp->getBody();

			if ($rawMessage) {
				return new Message(json_decode($rawMessage, true)['result']);
			}
		}

		return new Message(['error' => json_decode($resp->getBody(), true)]);
	}

	public function getUpdates(array $opts = []): Collection
	{
		$resp = $this->genericRequest('getUpdates', $opts);

		if ($resp->getStatusCode() === 200) {
			$result = collect();
			$updates = json_decode($resp->getBody(), true);

			foreach ($updates['result'] ?? [] as $update) {
				$result->push(new Update($update));
			}

			return $result;
		}

		return collect([
			new Update(['error' => json_decode($resp->getBody(), true)])
		]);
	}

	public function answerInlineQuery(
		array $opts = [],
		InlineQuery $inlineQuery = null
	): bool {
		if ($inlineQuery and !($opts['inline_query_id'] ?? false)) {
			$opts['inline_query_id'] = $inlineQuery->id;
		}

		$resp = $this->genericRequest('answerInlineQuery', $opts);

		return $resp->getStatusCode() === 200;
	}
}
