<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model implements Castable
{
	protected $fillable = [
		'id',
		'type',
		'title',
		'username',
		'first_name',
		'last_name',
		'photo',
		'bio',
		'description',
		'invite_link',
		'pinned_message',
		'permissions',
		'slow_mode_delay',
		'sticker_set_name',
		'can_set_sticker_set',
		'linked_chat_id',
		'location',
	];

	protected $casts = [
		'id' => 'integer',
		/* 'photo' => ChatPhoto::class, */
		'pinned_message' => Message::class,
		/* 'permissions' => ChatPermissions::class, */
		'slow_mode_delay' => 'integer',
		'can_set_sticker_set' => 'boolean',
		'linked_chat_id' => 'integer',
		/* 'location' => ChatLocation::class */
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
						return new Chat($value);
					else
						return new Chat(json_decode($value, true));
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
