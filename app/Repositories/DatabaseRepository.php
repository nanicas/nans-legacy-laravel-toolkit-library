<?php

namespace Zevitagem\LaravelToolkit\Repositories;

use Zevitagem\LaravelToolkit\Repositories\AbstractRepository;

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
