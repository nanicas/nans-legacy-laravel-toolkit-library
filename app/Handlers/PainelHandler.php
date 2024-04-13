<?php

namespace Nanicas\LegacyLaravelToolkit\Handlers;

use Nanicas\LegacyLaravelToolkit\Handlers\AbstractHandler;
use Nanicas\LegacyLaravelToolkit\Helpers\Helper;
use Nanicas\LegacyLaravelToolkit\Models\Rule;

class PainelHandler extends AbstractHandler
{
    public function afterGetRulesByApplication()
    {
        $rules = & $this->data;
    }
}
