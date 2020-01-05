<?php

namespace Laravelha\News\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider
{
    const SRC_PATH = __DIR__.'/..';

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->loadViewsFrom(self::SRC_PATH.'/../resource/view', 'news');
        $this->loadTranslationsFrom(self::SRC_PATH.'/../resource/lang', 'news');
        $this->loadMigrationsFrom(self::SRC_PATH.'/../database/migrations');
        $this->app->make(Factory::class)->load(self::SRC_PATH.'/../database/factories');

        $this->publishes([
            self::SRC_PATH.'/../resource/view' => resource_path('views/vendor/news'),
        ], 'news-views');

        $this->publishes([
            self::SRC_PATH.'/../resource/lang' => resource_path('lang/vendor/news'),
        ], 'news-lang');

        $this->publishes([
            self::SRC_PATH.'/../config/news.php' => config_path('news.php'),
        ], 'news-config');
    }

    /**
     * Register the package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            self::SRC_PATH.'/../config/news.php',
            'news'
        );
    }
}
