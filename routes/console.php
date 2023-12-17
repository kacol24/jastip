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
    sleep(1);
    $this->call('config:cache');
    sleep(1);
    $this->call('route:cache');
    sleep(1);
    $this->call('view:cache');
    sleep(1);
    $this->call('event:cache');
    sleep(1);
    $this->call('queue:restart');
    sleep(1);

    $this->call('icons:cache');
})->purpose('Cache and optimize. Used for production.');

Artisan::command('deploy:clear', function () {
    $this->call('optimize:clear');
    sleep(1);
    $this->call('config:clear');
    sleep(1);
    $this->call('route:clear');
    sleep(1);
    $this->call('view:clear');
    sleep(1);
    $this->call('event:clear');
    sleep(1);
    $this->call('queue:restart');
    sleep(1);

    $this->call('icons:clear');
})->purpose('Clears all cache and optimizations.');
