<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers;

use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithService;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

$config = InternalHelper::readTemplateConfig();
if (!empty($config['helpers'])) {
    class_alias($config['helpers']['global'],  __NAMESPACE__ . '\SCxxHelperAlias');
}

if (!empty($config['controllers'])) {
    class_alias($config['controllers']['base'], __NAMESPACE__ . '\BaseControllerAlias');
}

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

        $data = $this->getService()->getDataToIndex($request);
        $view = ($data['config']['page'] == 'contracted') ? 'pages.site.themes.' . $theme : 'pages.site.' . $data['config']['page'];

        $this->addIndexAssets();
        $this->beforeView();
        $packaged = $this->isPackagedView();

        return SCxxHelperAlias::view($view, $data, $packaged);
    }
}
