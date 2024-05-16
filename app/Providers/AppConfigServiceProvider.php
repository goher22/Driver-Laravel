<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use App\Elmas\Tools\PassportInstaller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {   
        $passport = new PassportInstaller;
        $passport->installKeys();

        app()->setLocale(setting('app.locale'));
    }
}
