<?php

namespace App\Models\InlineMode;

class InlineQueryResultGame extends InlineQueryResult
{
	protected $fillable = [
		'game_short_name',
		'reply_markup'
	];

	protected $casts = [
		'reply_markup' => 'array'
	];

	public function __construct(array $attrs = [])
	{
		parent::__construct($attrs);

		$this->type = 'game';
	}
}
