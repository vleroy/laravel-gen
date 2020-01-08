<?php


namespace Vleroy\Gen;

use Illuminate\Support\ServiceProvider;

class GenServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenCommand::class,
            ]);
        }
    }
}
