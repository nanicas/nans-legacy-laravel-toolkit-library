<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories;

use Nanicas\LegacyLaravelToolkit\Repositories\AbstractCrudRepository;
use Nanicas\LegacyLaravelToolkit\Models\Historic;

class HistoricRepository extends AbstractCrudRepository
{
    public function __construct()
    {
        parent::setModel(new Historic());
    }
}
