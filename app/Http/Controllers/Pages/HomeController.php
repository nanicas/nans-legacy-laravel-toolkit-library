<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers\Pages;

use Nanicas\LegacyLaravelToolkit\Services\HomeService;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], __NAMESPACE__ . '\HCxxHelperAlias');
class_alias(InternalHelper::readTemplateConfig()['controllers']['dashboard'], __NAMESPACE__ . '\DashboardControllerAlias');

class HomeController extends DashboardControllerAlias
{
    public function __construct(HomeService $homeService)
    {
        parent::__construct();

        $this->setService($homeService);
    }

    public function index()
    {
        $this->addIndexAssets();
        $this->beforeView();

        $data = $this->getService()->getDataToIndex();
        $packaged = $this->isPackagedView();

        return HCxxHelperAlias::view('pages.home.index', $data, $packaged);
    }
}
