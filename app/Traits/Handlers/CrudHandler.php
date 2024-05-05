<?php

namespace Nanicas\LegacyLaravelToolkit\Traits\Handlers;

trait CrudHandler
{
    public function store()
    {
        $data = &$this->data;
        $this->form($data);
    }

    public function update()
    {
        $data = &$this->data;
        $this->form($data);
    }

    public function destroy()
    {
        $data = &$this->data;

        $data['row'] = (isset($data['row'])) ? $data['row'] : null;
    }

    public function filter()
    {
        $data = &$this->data;

        if (
            array_key_exists('ids', $data) &&
            $ids = json_decode($data['ids'])
            // json_validate($data['ids']) @ref: https://www.php.net/manual/en/function.json-validate.php
        ) {
            $data['ids'] = array_unique(array_map(function ($value) {
                return (int) trim($value);
            }, $ids));
        }
    }

    public function form(&$data)
    {
    }
}
