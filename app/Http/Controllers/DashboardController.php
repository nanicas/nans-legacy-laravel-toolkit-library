<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class_alias(Helper::readTemplateConfig()['controllers']['base'],  __NAMESPACE__ . '\BaseControllerAlias');

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
        return Helper::notAllowedResponse($request);
    }
    
    public function beforeView(Request $request)
    {
        $sessionData = [];//Helper::getSessionData();

        View::share('session_data', $sessionData);
        View::share('is_admin', Helper::isAdmin());
        View::share('is_master', Helper::isMaster());
        View::share('is_test', Helper::isTest());
        View::share('is_worker', Helper::isWorker());
        View::share('dashboard_flash_data', $request->session()->get('dashboard_flash_data', null));

        parent::beforeView($request);
    }
}