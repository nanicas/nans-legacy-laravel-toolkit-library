<?php

namespace Zevitagem\LaravelToolkit\Repositories;

use Zevitagem\LaravelToolkit\Repositories\AbstractCrudRepository;
use Zevitagem\LaravelToolkit\Models\Historic;

class HistoricRepository extends AbstractCrudRepository
{
    public function __construct()
    {
        parent::setModel(new Historic());
    }
}
