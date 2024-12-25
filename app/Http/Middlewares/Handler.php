<?php

namespace App\Http\Middlewares;

use Illuminate\Foundation\Configuration\Middleware as BaseMiddleware;

class Handler
{
    protected array $aliases = [];

    public function __invoke(BaseMiddleware $middleware): BaseMiddleware
    {
        if ($this->aliases) {
            $middleware->alias($this->aliases);
        }

        return $middleware;
    }
}

