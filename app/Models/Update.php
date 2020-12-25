<?php

namespace App\Models;

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
		'inline_query' => InlineQuery::class,
		/* 'chosen_inline_result' => 'array', */
		/* 'callback_query' => 'array', */
		/* 'shipping_query' => 'array', */
		/* 'pre_checkout_query' => 'array', */
		/* 'poll' => 'array', */
		/* 'poll_answer' => 'array', */
		'was_processed' => 'boolean'
	];

	public function getTypeAttribute(): string
	{
		$keys = [
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

		foreach ($keys as $key) {
			if ($this->$key) return $key;
		}

		return 'unknown';
	}

	public static function getRules(): array
	{
		return [
			'update_id' => [
				'required',
				'integer'
			],
			'message' => [
				'nullable',
				'array'
			],
			'edited_message' => [
				'nullable',
				'array'
			],
			'channel_post' => [
				'nullable',
				'array'
			],
			'edited_channel_post' => [
				'nullable',
				'array'
			],
			'inline_query' => [
				'nullable',
				'array'
			],
			'chosen_inline_result' => [
				'nullable',
				'array'
			],
			'callback_query' => [
				'nullable',
				'array'
			],
			'shipping_query' => [
				'nullable',
				'array'
			],
			'pre_checkout_query' => [
				'nullable',
				'array'
			],
			'poll' => [
				'nullable',
				'array'
			],
			'poll_answer' => [
				'nullable',
				'array'
			],
		];
	}
}
