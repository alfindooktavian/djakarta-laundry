<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [];
    protected $middlewareGroups = [
        'web' => [],
        'api' => [],
    ];
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'role' => \App\Middleware\CheckRole::class,
    ];
}
