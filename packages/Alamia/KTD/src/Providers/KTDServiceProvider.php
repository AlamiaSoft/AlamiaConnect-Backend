<?php

namespace Alamia\KTD\Providers;

use Illuminate\Support\ServiceProvider;

class KTDServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function register()
    {
        //
    }
}
