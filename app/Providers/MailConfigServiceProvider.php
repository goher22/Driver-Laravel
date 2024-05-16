<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use anlutro\LaravelSettings\SettingStore as Setting;

class MailConfigServiceProvider extends ServiceProvider
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
        Config::set('mail.driver', setting('email.MAIL_DRIVER'));
        Config::set('mail.from', ['name' => setting('email.MAIL_FROM_NAME'), 'address' => setting('email.MAIL_FROM_ADDRESS')]);
        Config::set('mail.host', setting('email.MAIL_HOST'));
        Config::set('mail.port', setting('email.MAIL_PORT'));
        Config::set('mail.username', setting('email.MAIL_USERNAME'));
        Config::set('mail.password', setting('email.MAIL_PASSWORD'));
        Config::set('mail.encryption', setting('email.MAIL_ENCRYPTION'));
    }
}
