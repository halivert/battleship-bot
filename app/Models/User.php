<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements
	AuthenticatableContract,
	AuthorizableContract,
	Castable
{
	use Authenticatable, Authorizable, HasFactory;

	protected $primaryKey = 'id';

	public $incrementing = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'is_bot',
		'first_name',
		'last_name',
		'username',
		'language_code',
		'can_join_groups',
		'can_read_all_group_messages',
		'supports_inline_queries',
	];

	protected $casts = [
		'id' => 'integer',
		'is_bot' => 'boolean',
		'can_join_groups' => 'boolean',
		'can_read_all_group_messages' => 'boolean',
		'supports_inline_queries' => 'boolean',
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

					return new User($value);
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
