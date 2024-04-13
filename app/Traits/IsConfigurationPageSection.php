<?php

namespace Zevitagem\LaravelToolkit\Traits;

use Illuminate\Support\Facades\View;

trait IsConfigurationPageSection
{
    public string $section = '';

    public function getAssetsPath()
    {
        return 'config/' . $this->getSectionName();
    }

    public function setSectionName(string $section)
    {
        $this->section = $section;
    }

    public function getSectionName()
    {
        return $this->section;
    }

    public function beforeView()
    {
        parent::beforeView();

        View::share('section', $this->getSectionName());
    }

    protected function onConstructSection()
    {
        $packagedRoot = $this->getRootFolderNameOfAssetsPackaged();
        
        $this->addJsAssets($packagedRoot . '/resources/layouts/config/index.js');
        $this->addCssAssets($packagedRoot . '/resources/layouts/config/index.css');

        $this->setSectionName($this->getScreen());
    }

    protected function createView(string $screen, string $view, array $data)
    {
        $screen = 'config.' . $screen;
        $data['tab_options'] = $this->getTabOptions();

        return parent::createView($screen, $view, $data);
    }
}
