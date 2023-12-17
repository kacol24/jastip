<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('deploy', function () {
    $this->call('optimize');
    $this->call('config:cache');
    $this->call('event:cache');
    $this->call('route:cache');
    $this->call('view:cache');
    $this->call('queue:restart');

    //$this->call('icons:cache');
})->purpose('Cache and optimize. Used for production.');

Artisan::command('deploy:clear', function () {
    $this->call('optimize:clear');
    $this->call('config:clear');
    $this->call('event:clear');
    $this->call('route:clear');
    $this->call('view:clear');
    $this->call('queue:restart');

    //$this->call('icons:clear');
})->purpose('Clears all cache and optimizations.');
