<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class SocialAuthConfigServiceProvider extends ServiceProvider
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
        $facebook = [
            'client_id'     => setting('social.facebook_client_id'),
            'client_secret' => setting('social.facebook_client_secret'),
            'redirect'      => url('login/facebook/callback')
        ];
        Config::set('services.facebook', $facebook);

        $google = [
            'client_id'     => setting('social.google_client_id'),
            'client_secret' => setting('social.google_client_secret'),
            'redirect'      => url('login/google/callback')
        ];
        Config::set('services.google', $google);

        $twitter = [
            'client_id'     => setting('social.twitter_client_id'),
            'client_secret' => setting('social.twitter_client_secret'),
            'redirect'      => url('login/twitter/callback')
        ];
        Config::set('services.twitter', $twitter);

        $linkedin = [
            'client_id'     => setting('social.linkedin_client_id'),
            'client_secret' => setting('social.linkedin_client_secret'),
            'redirect'      => url('login/linkedin/callback')
        ];
        Config::set('services.linkedin', $linkedin);

        $github = [
            'client_id'     => setting('social.github_client_id'),
            'client_secret' => setting('social.github_client_secret'),
            'redirect'      => url('login/github/callback')
        ];
        Config::set('services.github', $github);

        $bitbucket = [
            'client_id'     => setting('social.bitbucket_client_id'),
            'client_secret' => setting('social.bitbucket_client_secret'),
            'redirect'      => url('login/bitbucket/callback')
        ];
        Config::set('services.bitbucket', $bitbucket);
    }
}
