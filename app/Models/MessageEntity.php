<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MessageEntity extends Model implements Castable
{
	protected $fillable = [
		'type',
		'offset',
		'length',
		'url',
		'user',
		'language',
		'final_value',
	];

	protected $casts = [
		'offset' => 'integer',
		'length' => 'integer',
		'user' => User::class,
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
					if (is_array($value))
						return new MessageEntity($value);
					else
						return new MessageEntity(json_decode($value, true));
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
