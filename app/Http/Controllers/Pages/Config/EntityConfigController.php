<?php

namespace Zevitagem\LaravelToolkit\Http\Controllers\Pages\Config;

use Zevitagem\LaravelToolkit\Services\Config\EntityConfigService;
use Illuminate\Http\Request;
use Zevitagem\LaravelToolkit\Helpers\Helper;

class_alias(Helper::readTemplateConfig()['controllers']['base_config'],  __NAMESPACE__ . '\BaseConfigControllerAlias');

class EntityConfigController extends BaseConfigControllerAlias
{
    public function __construct(EntityConfigService $service)
    {
        parent::__construct();

        $this->setService($service);
        $this->onConstructSection();
    }
    
    public function dynamicFormByComponent(Request $request)
    {
        $id = $request->get('id');
        $componentId = $request->get('component_id');

        try {
            $data    = $this->getService()->getDataToDynamicForm($componentId, $id);
            $message = '';
        } catch (\Exception $ex) {
            $data    = [];
            $message = $ex->getMessage();
        }

        return $this->createView('', 'entity_config.form-by-component', compact('data', 'message'));
    }
}
