<?php

namespace Zevitagem\LaravelToolkit\Http\Controllers\Pages\Config;

use Zevitagem\LaravelToolkit\Services\Config\CategoryConfigService;
use Zevitagem\LaravelToolkit\Helpers\Helper;

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