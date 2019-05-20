<?php

namespace App\Providers;

use App\House;
use App\Category;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      view()->composer('*', function ($view) {
        $view->with('House', app()->make(House::class));
        $view->with('Category', app()->make(Category::class));
        $view->with('User', app()->make(User::class));
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
