<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['controllers']['base'], __NAMESPACE__ . '\BaseControllerAlias');

class AllowanceController extends BaseControllerAlias
{
    public function not(Request $request)
    {
        parent::beforeView($request);

        return view('pages.allowance.not_allowed');
    }
}
