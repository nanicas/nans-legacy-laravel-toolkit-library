<?php

namespace Zevitagem\LaravelToolkit\Handlers\Config;

use Zevitagem\LaravelToolkit\Handlers\AbstractHandler;

class AddressConfigHandler extends AbstractHandler
{
    public function form(&$data)
    {
        $intKeys = [
            'number', 'zipcode',
        ];
        $stringWithNullKeys = [
            'phone', 'cellphone', 'email', 'observation'
        ];
        $stringNoNullKeys = [
            'latitude', 'longitude', 'country', 'state', 'city', 'street', 'open_at', 'close_at'
        ];

        foreach ($intKeys as $key) {
            $data[$key] = (isset($data[$key])) ? intval(trim($data[$key])) : null;
        }

        foreach (array_merge($stringNoNullKeys, $stringWithNullKeys) as $key) {
            $trimed = (isset($data[$key])) ? trim($data[$key]) : null;
            $data[$key] = (empty($trimed)) ? null : $trimed;
        }
    }

    public function store()
    {
        $data = & $this->data;
        $data['slug_config_id'] = $data['slug_config_id'] ?? $data['slug'];

        $this->form($data);
    }

    public function update()
    {
        $data = & $this->data;
        $data['id'] = $data['unique_id'] ?? 0;

        $this->form($data);
    }
}
