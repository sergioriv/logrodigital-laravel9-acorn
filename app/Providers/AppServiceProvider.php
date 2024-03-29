<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // Paginator::useBootstrap();
        // Paginator::useBootstrapThree();
        Paginator::useBootstrapFour();
        // Paginator::useBootstrapFive();
        // Paginator::useTailwind();

        Blade::directive('thousands', function ($amount) {
            return "<?= number_format($amount, 0); ?>";
        });
    }
}
