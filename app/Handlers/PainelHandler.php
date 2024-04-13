<?php

namespace Zevitagem\LaravelToolkit\Handlers;

use Zevitagem\LaravelToolkit\Handlers\AbstractHandler;
use Zevitagem\LaravelToolkit\Helpers\Helper;
use Zevitagem\LaravelToolkit\Models\Rule;

class PainelHandler extends AbstractHandler
{
    public function afterGetRulesByApplication()
    {
        $rules = & $this->data;
    }
}
