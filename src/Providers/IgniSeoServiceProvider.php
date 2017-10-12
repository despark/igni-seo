<?php

namespace Despark\Cms\Seo\Providers;

use Despark\Cms\Seo\Contracts\Seoable;
use Despark\Cms\Seo\Models\Seo;
use Illuminate\Support\ServiceProvider;

class IgniSeoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->app->bind(Seoable::class, Seo::class);
    }
}
