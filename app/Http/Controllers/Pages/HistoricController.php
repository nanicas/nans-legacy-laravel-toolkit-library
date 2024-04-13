<?php

namespace Zevitagem\LaravelToolkit\Http\Controllers\Pages;

use Zevitagem\LaravelToolkit\Services\Historic\HistoricService;
use Zevitagem\LaravelToolkit\Helpers\Helper;

class_alias(Helper::readTemplateConfig()['controllers']['crud'],  __NAMESPACE__ . '\CrudControllerAlias');

class HistoricController extends CrudControllerAlias
{
    public function __construct(HistoricService $service)
    {
        parent::__construct();

        $this->configureIndex('packaged', true);
        $this->setService($service);
    }
    
    public function addFormAssets()
    {
        parent::addFormAssets();
        
        $packagedRoot = $this->getRootFolderNameOfAssetsPackaged();

        $this->config['assets']['js'][]  = $packagedRoot . '/vendor/select2/js/select2.min.js';
        $this->config['assets']['css'][] = $packagedRoot . '/vendor/select2/css/select2.min.css';
    }
}