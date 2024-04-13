<?php

namespace Zevitagem\LaravelToolkit\Handlers\Config;

use Zevitagem\LaravelToolkit\Handlers\AbstractHandler;
use Zevitagem\LaravelToolkit\Helpers\Helper;

class ComponentConfigHandler extends AbstractHandler
{

    public function form(&$data)
    {
        $data['name'] = trim($data['name']);
        $data['template'] = trim($data['template']);
        $data['key'] = trim($data['key']);

        if (empty($data['key'])) {
            $data['key'] = Helper::generateCleanSnakeText($data['name']);
        }

        if (empty($data['template'])) {
            $data['template'] = null;
        }

        $data['category_id'] = intval($data['category']);
        $data['active'] = (int) (isset($data['active']) ? boolval($data['active']) : false);
        $data['has_title'] = (int) (isset($data['has_title']) ? boolval($data['has_title']) : false);
        $data['has_image'] = (int) (isset($data['has_image']) ? boolval($data['has_image']) : false);
        $data['has_content'] = (int) (isset($data['has_content']) ? boolval($data['has_content']) : false);
        $data['has_extra'] = (int) (isset($data['has_extra']) ? boolval($data['has_extra']) : false);
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
