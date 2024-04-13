<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories;

use Nanicas\LegacyLaravelToolkit\Repositories\AbstractRepository;

abstract class DatabaseRepository extends AbstractRepository
{
    public function getTable()
    {
        return $this->getModel()->getTable();
    }

    public function newException($exc)
    {
        throw $exc;
    }
}
