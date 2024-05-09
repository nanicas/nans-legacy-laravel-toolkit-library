<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['controllers']['base'], __NAMESPACE__ . '\BaseControllerAlias');

class ErrorController extends BaseControllerAlias
{
    public function generic(Request $request, string $message)
    {
        parent::beforeView($request);

        return view('pages.error.danger', compact('message'));
    }
}
