<?php

namespace Alamia\Admin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'contacts.person.create.after' => [
            'Alamia\Admin\Listeners\Person@linkToEmail',
        ],

        'lead.create.after' => [
            'Alamia\Admin\Listeners\Lead@linkToEmail',
        ],

        'activity.create.after' => [
            'Alamia\Admin\Listeners\Activity@afterUpdateOrCreate',
        ],

        'activity.update.after' => [
            'Alamia\Admin\Listeners\Activity@afterUpdateOrCreate',
        ],
    ];
}
