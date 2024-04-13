<?php

namespace Zevitagem\LaravelToolkit\Http\Controllers;

use Zevitagem\LaravelToolkit\Traits\AvailabilityWithService;
use Zevitagem\LaravelToolkit\Helpers\Helper;

class_alias(Helper::readTemplateConfig()['controllers']['base'],  __NAMESPACE__ . '\BaseControllerAlias');

abstract class SiteController extends BaseControllerAlias
{
    use AvailabilityWithService;
    
    public function __construct()
    {
        parent::__construct();
        
        $packagedRoot = $this->getRootFolderNameOfAssetsPackaged();
        
        $this->config['assets']['js'][] = $packagedRoot . '/js/site.js';
    }

    public function addIndexAssets()
    {
        return;
    }
}