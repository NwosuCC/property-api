<?php

namespace App\Providers;

use App\House;
use App\Category;
use App\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
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
      Schema::defaultStringLength(191);

      view()->composer('*', function ($view) {
        $view->with('House', app()->make(House::class));
        $view->with('Category', app()->make(Category::class));
        $view->with('User', app()->make(User::class));
      });


      /*---------------------------------------------------------------------------------------------*
       | BladeCompiler: vendor/laravel/framework/src/Illuminate/View/Compilers/BladeCompiler.php
       *------------------------------------------------------------------------------------------*/
      Blade::directive('datetime', function ($expression) {
        return "<?php echo ($expression)->format('M j, Y'); ?>";
      });

      Blade::directive('dayDatetime', function ($expression) {
        return "<?php echo ($expression)->format('D, M j, Y'); ?>";
      });

      /*Blade::directive('pushOnce', function ($expression) {
        return "<?php \$__env->startPush($expression); ?>";
      });*/
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
