<?php

namespace App\Traits;

use App\Models\WebhookInfo;
use Psr\Http\Message\ResponseInterface;

trait HasWebhook
{
	public function initializeHasWebhook()
	{
		$this->fillable[] = 'webhook';
	}

	public function getWebhookInfo(): WebhookInfo
	{
		$resp = $this->genericRequest('getWebhookInfo');

		if ($resp->getStatusCode() === 200) {
			$rawUser = $resp->getBody();

			if ($rawUser) {
				return new WebhookInfo(json_decode($rawUser, true)['result']);
			}
		}

		return new WebhookInfo([
			'error' => json_decode($resp->getBody(), true)
		]);
	}

	public function getWebhookUrlAttribute($value): ?string
	{
		if ($value) return $value;

		$webhookInfo = $this->getWebhookInfo();

		if ($webhookInfo->has_error) return null;

		return ($this->webhook_url = $webhookInfo->url);
	}

	public function setWebhook(
		string $url,
		array $opts = []
	): ResponseInterface {
		return $this->genericRequest('setWebhook', [
			'url' => $url
		] + $opts);
	}

	public function deleteWebhook(
		bool $dropPendingUpdates = false
	): ResponseInterface {
		return $this->genericRequest('deleteWebhook', [
			'drop_pending_updates' => $dropPendingUpdates
		]);
	}
}
