<?php

namespace EloquentTraits\Providers;

use Illuminate\Support\ServiceProvider;

class EloquentTraitsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'eloquent-traits');
    }
}
