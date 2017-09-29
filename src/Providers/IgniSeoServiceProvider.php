<?php

namespace Despark\Cms\ContactUs\Providers;

use Illuminate\Support\ServiceProvider;

class IgniSeoServiceProvider extends ServiceProvider
{
    /**
     * Artisan commands.
     *
     * @var array
     */
    protected $commands = [
        \Despark\Cms\ContactUs\Console\Commands\InstallCommand::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/' => config_path(),
        ], 'config');
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views'),
        ], 'views');
    }
}
