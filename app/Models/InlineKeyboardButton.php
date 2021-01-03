<?php

namespace App\Models;

class InlineKeyboardButton extends Model
{
	protected $fillable = [
		'text',
		'url',
		'login_url',
		'callback_data',
		'switch_inline_query',
		'switch_inline_query_current_chat',
		'callback_game',
		'pay'
	];

	protected $casts = [
		/* 'login_url' => LoginUrl::class, */
		/* 'callback_game' => CallbackGame::class, */
		'pay' => 'boolean'
	];

	public function __construct(array $attrs = [])
	{
		parent::__construct($attrs);
	}
}
