<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper;

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