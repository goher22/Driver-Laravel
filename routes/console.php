<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('elmas:refresh', function() {
    exec('rm ' . storage_path('logs/laravel*'));
    $this->info('Logs cleared!');

    exec('rm ' . storage_path('framework/sessions/*'));
    $this->info('Session files cleared!');

    Artisan::call('route:clear');
    $this->info('Route cache cleared!');

    Artisan::call('cache:clear');
    $this->info('Application cache cleared!');

    Artisan::call('config:clear');
    $this->info('Configuration cache cleared!');

    Artisan::call('view:clear');
    $this->info('Compiled views cleared!');

})->describe('Clear logs, sessions, route, cache, config and view');

Artisan::command('elmas:settings', function () {

    //App Settings
    setting(['app.name' => "Elmas User Management"])->save();
    setting(['app.locale' => "en"])->save();
    setting(['app.default_role' => 2])->save();
    setting(['app.enable_api' => false])->save();

    //Auth Settings
    setting(['auth.allow_registration' => true])->save();
    setting(['auth.email_verification' => false])->save();
    setting(['auth.remember_me' => true])->save();
    setting(['auth.forgot_password' => true])->save();

    //Mail Settings
    setting(['email.MAIL_DRIVER' => "smtp"])->save();
    setting(['email.MAIL_FROM_NAME' => "Admin"])->save();
    setting(['email.MAIL_FROM_ADDRESS' => "admin@admin.com"])->save();
    setting(['email.MAIL_HOST' => "mail.mailtrap.io"])->save();
    setting(['email.MAIL_PORT' => "25252"])->save();
    setting(['email.MAIL_USERNAME' => "username"])->save();
    setting(['email.MAIL_PASSWORD' => "secret"])->save();
});

Artisan::command('elmas:install', function () {
    Artisan::call('elmas:settings');
    $this->info('Default settings successfully installed!');

    try {
    	Artisan::call('key:generate', ["--force"=> true]);
    	$this->info('Application key set successfully.');
    } catch (Exception $e) {
    	$this->error("Key generation is not completed!");
        $this->line($e->getMessage());
        exit;
    }

    try {
    	Artisan::call('migrate', ["--force"=> true]);
    	$this->info('Migrated.');
    } catch (Exception $e) {
        $this->error("Migration is not completed!");
    	$this->line($e->getMessage());
        exit;
    }

    try {
    	Artisan::call('db:seed', ["--force"=> true]);
    	$this->info('Database seeding completed successfully.');
    } catch (Exception $e) {
        $this->error("Seeding is not completed!");
    	$this->line($e->getMessage());
        exit;
    }

    $this->info('Installation completed!');
})->describe('Install Elmas Application');
