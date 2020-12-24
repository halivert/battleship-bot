<?php

namespace App\Providers;

use App\Models\Update;
use App\Observers\UpdateObserver;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		/* \App\Events\ExampleEvent::class => [ */
		/* 	\App\Listeners\ExampleListener::class, */
		/* ], */
	];

	public function boot()
	{
		Update::observe(UpdateObserver::class);
	}
}
