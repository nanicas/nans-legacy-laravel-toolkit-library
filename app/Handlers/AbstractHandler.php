<?php

namespace Nanicas\LegacyLaravelToolkit\Handlers;

class AbstractHandler
{
    protected $data;
    protected $request;
    
    public function setRequest(object $request)
    {
        $this->request = $request;
    }

    public function setData(mixed &$data)
    {
        $this->data = & $data;
    }

    public function run(string $method = '')
    {
        if (!method_exists($this, $method)) {
            return;
        }
        
        $this->{$method}();
    }
}