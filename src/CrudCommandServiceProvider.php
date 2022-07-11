<?php

namespace Kraved\CrudCommand;

use Illuminate\Support\ServiceProvider;
use Kraved\CrudCommand\Console\MakeCrudEntitiesCommand;

class CrudCommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeCrudEntitiesCommand::class,
            ]);
        }
    }

    public function register()
    {

    }
}