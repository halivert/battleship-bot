<?php

namespace App\Traits;

use App\Models\WebhookInfo;

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

	public function getWebhookUrlAttribute($value): string
	{
		if ($value) return $value;

		return ($this->webhook_url = $this->getWebhookInfo()->url);
	}

	public function setWebhook(string $url, array $opts = [])
	{
		return $this->genericRequest('setWebhook', [
			'url' => $url
		] + $opts);
	}

	public function deleteWebhook(bool $dropPendingUpdates = false)
	{
		return $this->genericRequest('deleteWebhook', [
			'drop_pending_updates' => $dropPendingUpdates
		]);
	}
}
