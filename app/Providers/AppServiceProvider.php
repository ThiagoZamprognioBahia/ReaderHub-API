<?php

namespace App\Providers;

use App\Models\BookReader;
use App\Observers\BookReaderObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        BookReader::observe(BookReaderObserver::class);
    }
}
