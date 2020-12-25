<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Message extends Model implements Castable
{
	protected $fillable = [
		'message_id',
		'from',
		'sender_chat',
		'date',
		'chat',
		'forward_from',
		'forward_from_chat',
		'forward_from_message_id',
		'forward_signature',
		'forward_sender_name',
		'forward_date',
		'reply_to_message',
		'via_bot',
		'edit_date',
		'media_group_id',
		'author_signature',
		'text',
		'entities',
		'animation',
		'audio',
		'document',
		'photo',
		'sticker',
		'video',
		'video_note',
		'voice',
		'caption',
		'caption_entities',
		'contact',
		'dice',
		'game',
		'poll',
		'venue',
		'location',
		'new_chat_members',
		'left_chat_member',
		'new_chat_title',
		'new_chat_photo',
		'delete_chat_photo',
		'group_chat_created',
		'supergroup_chat_created',
		'channel_chat_created',
		'migrate_to_chat_id',
		'migrate_from_chat_id',
		'pinned_message',
		'invoice',
		'successful_payment',
		'connected_website',
		'passport_data',
		'proximity_alert_triggered',
		'reply_markup',
	];

	protected $casts = [
		'message_id' => 'integer',
		'from' => User::class,
		'sender_chat' => Chat::class,
		'date' => 'integer',
		'chat' => Chat::class,
		'forward_from' => User::class,
		'forward_from_chat' => Chat::class,
		'forward_from_message_id' => 'integer',
		'forward_date' => 'integer',
		'reply_to_message' => Message::class,
		'via_bot' => User::class,
		'edit_date' => 'integer',
		/* 'animation' => Animation::class, */
		/* 'audio' => Audio::class, */
		/* 'document' => Document::class, */
		'photo' => 'array',
		/* 'sticker' => Sticker::class, */
		/* 'video' => Video::class, */
		/* 'video_note' => VideoNote::class, */
		/* 'voice' => Voice::class, */
		'caption_entities' => 'array',
		/* 'contact' => Contact::class, */
		/* 'dice' => Dice::class, */
		/* 'game' => Game::class, */
		/* 'poll' => Poll::class, */
		/* 'venue' => Venue::class, */
		/* 'location' => Location::class, */
		'new_chat_members' => 'array',
		'left_chat_member' => User::class,
		'new_chat_photo' => 'array',
		'delete_chat_photo' => 'boolean',
		'group_chat_created' => 'boolean',
		'supergroup_chat_created' => 'boolean',
		'channel_chat_created' => 'boolean',
		'migrate_to_chat_id' => 'integer',
		'migrate_from_chat_id' => 'integer',
		'pinned_message' => Message::class,
		/* 'invoice'  => Invoice::class, */
		/* 'successful_payment' => SuccessfulPayment::class, */
		/* 'passport_data' => PassportData::class, */
		/* 'proximity_alert_triggered' => ProximityAlertTriggered::class, */
		/* 'reply_markup' => InlineKeyboardMarkup::class, */
	];

	public function getEntitiesAttribute($value): Collection
	{
		if ($value) {
			return collect($value)->map(function ($i) {
				$entity = new MessageEntity($i);

				$entity->final_value = Str::substr(
					$this->text,
					$entity->offset + 1,
					$entity->length
				);

				return $entity;
			});
		}

		return collect();
	}

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

					return new Message($value);
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
