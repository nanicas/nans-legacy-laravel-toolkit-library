<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], __NAMESPACE__ . '\HelperAlias');

class OnlyAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (HelperAlias::isAdmin()) {
            return $next($request);
        }

        return HelperAlias::notAllowedResponse($request);
    }
}
