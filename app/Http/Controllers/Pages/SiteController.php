<?php

namespace Zevitagem\LaravelToolkit\Http\Controllers\Pages;

use Zevitagem\LaravelToolkit\Services\Site\SiteService;
use Zevitagem\LaravelToolkit\Helpers\Helper;
use Illuminate\Http\Request;

class_alias(Helper::readTemplateConfig()['controllers']['site'],  __NAMESPACE__ . '\SiteBaseAlias');

class SiteController extends SiteBaseAlias
{
    public function __construct(SiteService $service)
    {
        $this->setService($service);

        parent::__construct();
    }

    public function index(Request $request, string $slug = '')
    {
        $theme = $request->query('theme') ?? 'zacson';

        $data = $this->getService()->getIndexData(['slug' => $slug]);
        $view = ($data['config']['page'] == 'contracted') ? 'pages.site.themes.' . $theme : 'pages.site.' . $data['config']['page'];

        $this->addIndexAssets();
        $this->beforeView();
        $packaged = $this->isPackagedView();

        return Helper::view($view, $data, $packaged);
    }
}
