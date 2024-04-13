<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers\Pages\Config;

use Nanicas\LegacyLaravelToolkit\Services\ConfigUserService;
use Illuminate\Http\Request;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper;

class_alias(Helper::readTemplateConfig()['controllers']['base_config'],  __NAMESPACE__ . '\BaseConfigControllerAlias');

class UserConfigController extends BaseConfigControllerAlias
{
    public function __construct(ConfigUserService $service)
    {
        parent::__construct();

        $this->setService($service);
        $this->onConstructSection();
    }

    public function index(Request $request)
    {
        return $this->show($request, Helper::getUserId());
    }
}
