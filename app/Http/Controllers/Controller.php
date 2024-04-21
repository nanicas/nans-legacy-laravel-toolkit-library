<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\View;
use Nanicas\LegacyLaravelToolkit\Traits\Configurable;
use Illuminate\Routing\Controller as BaseController;
use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithService;
use Nanicas\LegacyLaravelToolkit\Staters\AppStater;
use Illuminate\Http\Request;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], __NAMESPACE__ . '\HelperAlias');

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    use Configurable,
        AvailabilityWithService;

    protected bool $isAPI = false;

    public function __construct()
    {
        $this->mergeConfig([
            'assets' => [
                'css' => [],
                'js' => []
            ]
        ]);

        if (!$this->existsConfigIndex('packaged')) {
            $this->configureIndex('packaged', false);
        }

        AppStater::setItem('packaged', $this->getConfigIndex('packaged'));
    }

    public function getIsAPI()
    {
        return $this->isAPI;
    }

    public function beforeView(Request $request)
    {
        View::share('assets', $this->getConfig()['assets'] ?? []);
        View::share('view_prefix', HelperAlias::getViewPrefix());
        View::share('assets_prefix', $this->getRootFolderNameOfAssets());
        View::share('packaged_assets_prefix', $this->getRootFolderNameOfAssetsPackaged());
        View::share('screen', $this->getScreen());
        View::share('full_screen', $this->getFullScreen());
        View::share('section_screen', $this->getSectionScreen());
        View::share('app_flash_data', $request->session()->get('app_flash_data', null));
    }

    public function getFullScreen(): string
    {
        $screen = $this->getScreen();
        $sectionScreen = $this->getSectionScreen();

        if (!empty($sectionScreen)) {
            $screen .= '.' . $sectionScreen;
        }

        return $screen;
    }

    public function getScreen(): string
    {
        list($main) = explode('.', \Request::route()->getName());
        return $main;
    }

    public function getSectionScreen(): string
    {
        $list = explode('.', \Request::route()->getName());
        $count = count($list);

        if ($count == 1) {
            return '';
        }

        array_pop($list);
        array_shift($list);

        return implode('.', $list);
    }

    public function addJsAssets(string $path)
    {
        $this->config['assets']['js'][] = $path;
    }

    public function addCssAssets(string $path)
    {
        $this->config['assets']['css'][] = $path;
    }

    public function definePathAssets()
    {
        return (method_exists($this, 'getAssetsPath')) ? $this->getAssetsPath() : $this->getScreen();
    }

    public function getControllerName()
    {
        $list = explode('\\', get_class($this));
        end($list);

        return strtolower(str_replace('Controller', '', current($list)));
    }

    public function isPackagedView()
    {
        return ($this->getConfigIndex('packaged') === true);
    }

    public function getRootFolderNameOfAssetsPackaged()
    {
        return HelperAlias::getRootFolderNameOfAssets();
    }

    public function getRootFolderNameOfAssets()
    {
        $root = HelperAlias::getRootFolderNameOfAssets();
        $packaged = $this->isPackagedView();

        return ($packaged) ? $root . '/' : '';
    }

    public function addIndexAssets()
    {
        $path = $this->definePathAssets();
        $root = $this->getRootFolderNameOfAssets();

        $this->config['assets']['js'][] = $root . 'resources/pages/' . $path . '/index.js';
        $this->config['assets']['css'][] = $root . 'resources/pages/' . $path . '/index.css';
    }

    public function addShowAssets()
    {
        if (method_exists($this, 'addFormAssets')) {
            $this->addFormAssets();
        }

        $path = $this->definePathAssets();
        $root = $this->getRootFolderNameOfAssets();

        $this->config['assets']['js'][] = $root . 'resources/pages/' . $path . '/show.js';
        $this->config['assets']['css'][] = $root . 'resources/pages/' . $path . '/show.css';
    }

    public function addCreateAssets()
    {
        if (method_exists($this, 'addFormAssets')) {
            $this->addFormAssets();
        }

        $path = $this->definePathAssets();
        $root = $this->getRootFolderNameOfAssets();

        $this->config['assets']['js'][] = $root . 'resources/pages/' . $path . '/create.js';
        $this->config['assets']['css'][] = $root . 'resources/pages/' . $path . '/create.css';
    }

    public function addListAssets()
    {
        $path = $this->definePathAssets();
        $root = $this->getRootFolderNameOfAssets();

        $this->config['assets']['js'][] = $root . 'resources/pages/' . $path . '/list.js';
        $this->config['assets']['css'][] = $root . 'resources/pages/' . $path . '/list.css';
    }

    protected function setIsAPI(bool $value)
    {
        $this->isAPI = $value;
    }
}
