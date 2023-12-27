<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('path.public', function () {
            // return '/home/samikary/admin.samikarya.com';
            return '/var/www/boedjangroup-public.com';
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            // $this->app['request']->server->set('HTTPS', $this->app->environment() == 'production');
            \URL::forceScheme('https');
        }

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();
    }
}
