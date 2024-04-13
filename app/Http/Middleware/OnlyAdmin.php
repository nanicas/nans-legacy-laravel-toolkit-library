<?php

namespace Zevitagem\LaravelToolkit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Zevitagem\LaravelToolkit\Helpers\Helper;

class OnlyAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Helper::isAdmin()) {
            return $next($request);
        }

        return Helper::notAllowedResponse($request);
    }
}