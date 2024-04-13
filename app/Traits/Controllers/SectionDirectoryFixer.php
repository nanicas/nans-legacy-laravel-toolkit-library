<?php

namespace Zevitagem\LaravelToolkit\Traits\Controllers;

trait SectionDirectoryFixer
{
    public function getFullScreen(): string
    {
        return parent::getScreen() . '.' . parent::getSectionScreen();
    }
    
    public function getAssetsPath()
    {
        return str_replace('.', '/', $this->getFullScreen());
    }
}
