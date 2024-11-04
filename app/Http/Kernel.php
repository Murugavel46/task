<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\CheckAuthenticated::class,
        'guest' => \App\Http\Middleware\CheckAuthenticated::class,
        'jwt.auth' => \App\Http\Middleware\CheckAuthenticated::class,
    ];
}
