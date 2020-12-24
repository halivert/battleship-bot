<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
	protected $primaryKey = 'update_id';

	public $incrementing = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'update_id',
		'message',
		'edited_message',
		'channel_post',
		'edited_channel_post',
		'inline_query',
		'chosen_inline_result',
		'callback_query',
		'shipping_query',
		'pre_checkout_query',
		'poll',
		'poll_answer',
	];

	protected $casts = [
		'update_id' => 'integer',
		'message' => Message::class,
		'edited_message' => Message::class,
		'channel_post' => Message::class,
		'edited_channel_post' => Message::class,
		/* 'inline_query' => 'array', */
		/* 'chosen_inline_result' => 'array', */
		/* 'callback_query' => 'array', */
		/* 'shipping_query' => 'array', */
		/* 'pre_checkout_query' => 'array', */
		/* 'poll' => 'array', */
		/* 'poll_answer' => 'array', */
	];

	public function getTypeAttribute(): string
	{
		if ($this->message) return 'message';
		if ($this->edited_message) return 'edited_message';
		if ($this->channel_post) return 'channel_post';
		if ($this->edited_channel_post) return 'edited_channel_post';
		if ($this->inline_query) return 'inline_query';
		if ($this->chosen_inline_result) return 'chosen_inline_result';
		if ($this->callback_query) return 'callback_query';
		if ($this->shipping_query) return 'shipping_query';
		if ($this->pre_checkout_query) return 'pre_checkout_query';
		if ($this->poll) return 'poll';
		if ($this->poll_answer) return 'poll_answer';

		return 'unknown';
	}
}
