<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

$config = InternalHelper::readTemplateConfig();
if (!empty($config['helpers'])) {
    class_alias($config['helpers']['global'], uniqid() . __NAMESPACE__ . '\HelperAlias');
}

if (!empty($config['controllers'])) {
    class_alias($config['controllers']['base'], __NAMESPACE__ . '\BaseControllerAlias');
}

abstract class DashboardController extends BaseControllerAlias
{
    protected bool $allowed = true;

    public function __construct()
    {
        parent::__construct();

        $root = $this->getRootFolderNameOfAssetsPackaged();

        $this->config['assets']['js'][] = $root . '/js/layouts/dashboard.js';
        $this->config['assets']['css'][] = $root . '/css/layouts/dashboard.css';
    }

    protected function allowed(bool $value)
    {
        $this->allowed = $value;
    }

    protected function isAllowed(): bool
    {
        return $this->allowed;
    }

    protected function notAllowedResponse(Request $request)
    {
        return HelperAlias::notAllowedResponse($request);
    }

    public function beforeView(Request $request)
    {
        View::share('is_admin', HelperAlias::isAdmin());
        View::share('is_master', HelperAlias::isMaster());
        View::share('is_test', HelperAlias::isTest());
        View::share('is_worker', HelperAlias::isWorker());
        View::share('dashboard_flash_data', $request->session()->get('dashboard_flash_data', null));

        parent::beforeView($request);
    }
}
