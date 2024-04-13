<?php

namespace Nanicas\LegacyLaravelToolkit\Handlers;

use Nanicas\LegacyLaravelToolkit\Handlers\AbstractHandler;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper;

class ConfigUserHandler extends AbstractHandler
{
    public function form(&$data)
    {
        $data['name']  = trim($data['name']);
        $data['admin'] = (int) (isset($data['admin']) ? boolval($data['admin']) : false);
    }

    public function store()
    {
        $data = & $this->data;

        $this->form($data);

        if (!Helper::isMaster()) {
            $data['admin'] = 0;
        }
    }

    public function update()
    {
        $data = & $this->data;

        $this->form($data);

        if (!Helper::isMaster()) {
            unset($data['admin']);
        }
    }
}