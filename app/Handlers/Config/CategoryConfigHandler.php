<?php

namespace Nanicas\LegacyLaravelToolkit\Handlers\Config;

use Nanicas\LegacyLaravelToolkit\Handlers\AbstractHandler;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper;
use Nanicas\LegacyLaravelToolkit\Traits\Handlers\CrudHandler;

class CategoryConfigHandler extends AbstractHandler
{
    use CrudHandler;
    
    public function form(&$data)
    {
        $data['name'] = trim($data['name']);
        $data['key'] = trim($data['key']);
        $data['active'] = (int) (isset($data['active']) ? boolval($data['active']) : false);

        if (empty($data['key'])) {
            $data['key'] = Helper::generateCleanSnakeText($data['name']);
        }
    }

    public function store()
    {
        $data = & $this->data;
        $data['slug'] = Helper::getSlug();

        $this->form($data);
    }

    public function update()
    {
        $data = & $this->data;

        $this->form($data);
    }
}
