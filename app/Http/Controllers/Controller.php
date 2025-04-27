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

$config = InternalHelper::readTemplateConfig();
if (!empty($config['helpers'])) {
    class_alias($config['helpers']['global'],  __NAMESPACE__ . '\CxxHelperAlias');
}

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    use Configurable,
        AvailabilityWithService;

    protected object $request;

    protected bool $isAPI = false;

    protected bool $packaged = false;

    protected bool $safe = true;

    protected string $view;

    protected bool $allowed = true;

    public function __construct()
    {
        $this->mergeConfig([
            'assets' => [
                'css' => [],
                'js' => []
            ]
        ]);

        AppStater::setItem('packaged', $this->packaged);
    }

    public function getIsAPI()
    {
        return $this->isAPI;
    }

    public function beforeView(Request $request, bool $sharePrefixes = true)
    {
        $templateConfig = CxxHelperAlias::readTemplateConfig();

        View::share('state', $request->query('state'));
        View::share('assets', $this->getConfig()['assets'] ?? []);
        View::share('screen', $this->getScreen());
        View::share('full_screen', $this->getFullScreen());
        View::share('template_config', $templateConfig);
        View::share('section_screen', $this->getSectionScreen());
        View::share('app_flash_data', $request->session()->get('app_flash_data', null));

        if ($sharePrefixes) {
            CxxHelperAlias::sharePrefixesBeforeView();
        }
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
        list($main) = explode('.', request()->route()->getName());
        return $main;
    }

    public function getSectionScreen(): string
    {
        $list = explode('.', request()->route()->getName());
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
        return CxxHelperAlias::isPackagedView();
    }

    public function getRootFolderNameOfAssetsPackaged()
    {
        return CxxHelperAlias::getRootFolderNameOfAssetsPackaged();
    }

    public  function getViewPrefixPackaged()
    {
        return CxxHelperAlias::getViewPrefixPackaged();
    }

    public  function getViewPrefix()
    {
        return CxxHelperAlias::getViewPrefix();
    }

    public function getRootFolderNameOfAssets()
    {
        return CxxHelperAlias::getRootFolderNameOfAssets();
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

    public function addEditAssets()
    {
        if (method_exists($this, 'addFormAssets')) {
            $this->addFormAssets();
        }

        $path = $this->definePathAssets();
        $root = $this->getRootFolderNameOfAssets();

        $this->config['assets']['js'][] = $root . 'resources/pages/' . $path . '/edit.js';
        $this->config['assets']['css'][] = $root . 'resources/pages/' . $path . '/edit.css';
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

    protected function setView(string $view)
    {
        $this->view = $view;
    }

    protected function getView()
    {
        return $this->view;
    }

    protected function allowed(bool $value)
    {
        $this->allowed = $value;
    }

    protected function isAllowed(): bool
    {
        return $this->allowed;
    }

    protected function setSafe(bool $value)
    {
        $this->safe = $value;
    }

    protected function isSafe()
    {
        return (!empty($this->safe));
    }
}
