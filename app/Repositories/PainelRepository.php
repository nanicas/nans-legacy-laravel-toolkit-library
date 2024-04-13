<?php

namespace Zevitagem\LaravelToolkit\Repositories;

use Zevitagem\LegoAuth\Repositories\PainelRepository as BasePainelRepository;
use Zevitagem\LaravelToolkit\Handlers\PainelHandler;

class PainelRepository extends BasePainelRepository
{
    public function __construct(PainelHandler $painelHandler)
    {
        $this->setDependencie(parent::PAINEL_HANDLER_KEY, $painelHandler);
    }
}
