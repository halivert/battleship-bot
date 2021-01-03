<?php

namespace App\Models\InlineMode;

use App\Models\Model;
use Illuminate\Support\Str;

class InlineQueryResult extends Model
{
	public function __construct(array $attrs = [])
	{
		parent::__construct($attrs);

		$this->fillable[] = 'type';
		$this->fillable[] = 'id';

		$this->casts['id'] = 'string';

		$this->id = Str::uuid();
	}
}
