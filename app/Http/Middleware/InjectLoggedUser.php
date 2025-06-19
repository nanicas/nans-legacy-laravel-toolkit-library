<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectLoggedUser
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $request->merge(['_logged_user_id' => auth()->id()]);
        }

        return $next($request);
    }
}
