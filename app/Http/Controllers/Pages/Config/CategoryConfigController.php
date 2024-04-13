<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers\Pages\Config;

use Nanicas\LegacyLaravelToolkit\Services\Config\CategoryConfigService;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper;

class_alias(Helper::readTemplateConfig()['controllers']['base_config'],  __NAMESPACE__ . '\BaseConfigControllerAlias');

class CategoryConfigController extends BaseConfigControllerAlias
{
    public function __construct(CategoryConfigService $service)
    {
        parent::__construct();

        $this->setService($service);
        $this->onConstructSection();
    }
}