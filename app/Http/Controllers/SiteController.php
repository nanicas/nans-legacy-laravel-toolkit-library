<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers;

use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithService;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], __NAMESPACE__ . '\HelperAlias');
class_alias(InternalHelper::readTemplateConfig()['controllers']['base'], __NAMESPACE__ . '\BaseControllerAlias');

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

    public function index(Request $request, string $slug = '')
    {
        $theme = $request->query('theme') ?? 'zacson';

        $data = $this->getService()->getIndexData($request);
        $view = ($data['config']['page'] == 'contracted') ? 'pages.site.themes.' . $theme : 'pages.site.' . $data['config']['page'];

        $this->addIndexAssets();
        $this->beforeView();
        $packaged = $this->isPackagedView();

        return HelperAlias::view($view, $data, $packaged);
    }
}
