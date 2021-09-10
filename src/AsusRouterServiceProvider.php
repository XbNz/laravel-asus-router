<?php

namespace XbNz\AsusRouter;


use XbNz\AsusRouter\Data\DataObject;
use XbNz\AsusRouter\Data\TestFeature;
use XbNz\AsusRouter\Data\Validators\ValidatorInterface;
use XbNz\AsusRouter\Data\Validators\WanValidator;
use XbNz\AsusRouter\Data\Wan;

class AsusRouterServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'router-config');

        $this->app->tag([WanValidator::class], 'data-validators');

        $this->app
            ->when(Wan::class)
            ->needs('$validators')
            ->giveTagged('data-validators');

    }


    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                    __DIR__.'/../config/config.php' =>
                    config_path('router-config.php')
            ], 'config');
        }
    }
}