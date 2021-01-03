<?php

namespace App\Models\InlineMode;

use App\Models\InputMessageContent;

class InlineQueryResultArticle extends InlineQueryResult
{
	protected $fillable = [
		'title',
		'input_message_content',
		'reply_markup',
		'url',
		'hide_url',
		'description',
		'thumb_url',
		'thumb_width',
		'thumb_height',
	];

	protected $casts = [
		'input_message_content' => InputMessageContent::class,
		/* 'reply_markup' => InlineKeyboardMarkup::class, */
		'hide_url' => 'boolean',
		'thumb_width' => 'integer',
		'thumb_height' => 'integer'
	];

	public function __construct(array $attrs = [])
	{
		parent::__construct($attrs);

		$this->type = 'article';
	}
}
