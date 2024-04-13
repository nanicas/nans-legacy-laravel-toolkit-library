<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories;

use Zevitagem\LegoAuth\Repositories\PainelRepository as BasePainelRepository;
use Nanicas\LegacyLaravelToolkit\Handlers\PainelHandler;

class PainelRepository extends BasePainelRepository
{
    public function __construct(PainelHandler $painelHandler)
    {
        $this->setDependencie(parent::PAINEL_HANDLER_KEY, $painelHandler);
    }
}
