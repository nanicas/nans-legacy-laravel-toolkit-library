<?php

namespace Zevitagem\LaravelToolkit\Handlers\Config;

use Zevitagem\LaravelToolkit\Handlers\AbstractHandler;
use Zevitagem\LaravelToolkit\Helpers\Helper;

class DataConfigHandler extends AbstractHandler
{
    public function form(&$data)
    {
        $stringWithNullKeys = [
            'facebook', 'youtube', 'instagram', 'twitter'
        ];
        
        foreach ($stringWithNullKeys as $key) {
            $trimed = (isset($data[$key])) ? trim($data[$key]) : null;
            $data[$key] = (empty($trimed)) ? null : $trimed;
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
