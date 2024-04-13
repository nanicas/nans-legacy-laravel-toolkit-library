<?php

namespace Nanicas\LegacyLaravelToolkit\Traits\Handlers;

trait CrudHandler
{
    public function store()
    {
        $data = & $this->data;
        $this->form($data);
    }

    public function update()
    {
        $data = & $this->data;
        $this->form($data);
    }

    public function destroy()
    {
        $data = & $this->data;

        $data['row'] = (isset($data['row'])) ? $data['row'] : null;
    }

    public function form(&$data)
    {
        
    }
}
