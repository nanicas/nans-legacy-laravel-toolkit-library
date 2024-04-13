<?php

namespace Zevitagem\LaravelToolkit\Repositories\Config;

use Zevitagem\LaravelToolkit\Repositories\AbstractCrudRepository;
use Zevitagem\LaravelToolkit\Models\Config\EntityConfig;

class EntityConfigRepository extends AbstractCrudRepository
{

    public function __construct()
    {
        parent::setModel(new EntityConfig());
    }

}
