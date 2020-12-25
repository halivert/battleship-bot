<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
	public function __construct(array $attrs = [])
	{
		$this->fillable[] = 'error';
		$this->casts['error'] = ErrorResponse::class;

		parent::__construct($attrs);
	}

	public function getHasErrorAttribute(): bool
	{
		return $this->error !== null;
	}

	public function getError(): ?ErrorResponse
	{
		return $this->error;
	}
}
