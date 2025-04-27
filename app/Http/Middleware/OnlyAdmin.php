<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], __NAMESPACE__ . '\OAMxxHelperAlias');

class OnlyAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (OAMxxHelperAlias::isAdmin()) {
            return $next($request);
        }

        return OAMxxHelperAlias::notAllowedResponse($request);
    }
}
