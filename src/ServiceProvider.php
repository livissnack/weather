<?php

/*
 * This file is part of the livissnack/weather.
 *
 * (c) livissnack <brucesnack@outlook.com>
 *
 * This source file is subject to the MIT license that is bundled with this source code in the file LICENSE.
 */

namespace Livissnack\Weather;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Weather::class, function () {
            return new Weather(config('services.weather.ak'), config('services.weather.sn'));
        });

        $this->app->alias(Weather::class, 'weather');
    }

    public function provides()
    {
        return [Weather::class, 'weather'];
    }
}
