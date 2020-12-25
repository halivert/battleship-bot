<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class InlineQuery extends Model implements Castable
{
	protected $fillable = [
		'id',
		'from',
		'location',
		'query',
		'offset',
	];

	protected $casts = [
		'from' => User::class,
		/* 'location' => Location::class, */
	];

	/**
	 * {@inheritDoc}
	 */
	public static function castUsing(array $arguments)
	{
		return new class implements CastsAttributes
		{
			/**
			 * {@inheritDoc}
			 */
			public function get($model, string $key, $value, array $attributes)
			{
				if ($value) {
					if (!is_array($value)) {
						$value = json_decode($value, true);
					}

					return new InlineQuery($value);
				}
				return null;
			}

			/**
			 * {@inheritDoc}
			 */
			public function set($model, string $key, $value, array $attributes)
			{
				return [
					$key => json_encode($value),
				];
			}
		};
	}
}

