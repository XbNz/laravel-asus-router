<?php

namespace XbNz\AsusRouter;


use XbNz\AsusRouter\Data\DataObject;
use XbNz\AsusRouter\Data\Validators\ValidatorInterface;
use XbNz\AsusRouter\Data\Validators\WanValidator;
use XbNz\AsusRouter\Data\Wan;

class AsusRouterServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {

//        $this->app->bind(ValidatorInterface::class, WanValidator::class);

        $this->app
            ->when(Wan::class)
            ->needs(ValidatorInterface::class)
            ->give(WanValidator::class);

//        $this->app->tag([WanValidator::class], 'data-validators');
    }


    public function boot()
    {

    }
}