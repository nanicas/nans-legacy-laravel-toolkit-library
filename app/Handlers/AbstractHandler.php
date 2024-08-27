<?php

namespace Nanicas\LegacyLaravelToolkit\Handlers;

use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithRequest;

abstract class AbstractHandler
{
    use AvailabilityWithRequest;

    protected $data;

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
