<?php

namespace App\Models;

class InputTextMessageContent extends InputMessageContent
{
	protected $fillable = [
		'message_text',
		'parse_mode',
		'entities',
		'disable_web_page_preview',
	];

	protected $casts = [
		'message_text' => 'string',
		'parse_mode' => 'string',
		'entities' => 'array',
		'disable_web_page_preview' => 'boolean'
	];
}
