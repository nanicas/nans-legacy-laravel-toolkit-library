<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

$config = InternalHelper::readTemplateConfig();

if (!empty($config['helpers'])) {
    class_alias($config['helpers']['global'],  __NAMESPACE__ . '\DCxxHelperAlias');
}

if (!empty($config['controllers'])) {
    class_alias($config['controllers']['base'], __NAMESPACE__ . '\BaseControllerAlias');
}

abstract class DashboardController extends BaseControllerAlias
{
    public function __construct()
    {
        parent::__construct();

        $root = $this->getRootFolderNameOfAssetsPackaged();

        $this->config['assets']['js'][] = $root . '/js/layouts/dashboard.js';
        $this->config['assets']['css'][] = $root . '/css/layouts/dashboard.css';
    }

    protected function notAllowedResponse(Request $request)
    {
        return DCxxHelperAlias::notAllowedResponse($request);
    }

    public function beforeView(Request $request, bool $sharePrefixes = true)
    {
        View::share('is_admin', DCxxHelperAlias::isAdmin());
        View::share('is_master', DCxxHelperAlias::isMaster());
        View::share('is_test', DCxxHelperAlias::isTest());
        View::share('is_worker', DCxxHelperAlias::isWorker());
        View::share('dashboard_flash_data', $request->session()->get('dashboard_flash_data', null));

        parent::beforeView($request, $sharePrefixes);
    }
}
