<?php

namespace App\Models;

class WebhookInfo extends Model
{
	protected $fillable = [
		'url',
		'has_custom_certificate',
		'pending_update_count',
		'ip_address',
		'last_error_date',
		'last_error_message',
		'max_connections',
		'allowed_updates',
	];

	protected $casts = [
		'has_custom_certificate' => 'boolean',
		'pending_update_count' => 'integer',
		'last_error_date' => 'integer',
		'max_connections' => 'integer',
		'allowed_updates' => 'array'
	];
}
