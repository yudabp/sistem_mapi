<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Custom blade directives for role checking
        Blade::if('superadmin', function () {
            return auth()->check() && auth()->user()->hasRole('superadmin');
        });

        Blade::if('direksi', function () {
            return auth()->check() && auth()->user()->hasRole('direksi');
        });

        Blade::if('canedit', function () {
            return auth()->check() && !auth()->user()->hasRole('direksi');
        });

        Blade::if('canexport', function () {
            return auth()->check() && auth()->user()->can('export-data');
        });
    }
}