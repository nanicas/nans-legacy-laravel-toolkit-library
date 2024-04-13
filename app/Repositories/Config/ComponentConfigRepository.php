<?php

namespace Zevitagem\LaravelToolkit\Repositories\Config;

use Zevitagem\LaravelToolkit\Repositories\AbstractCrudRepository;
use Zevitagem\LaravelToolkit\Models\Config\ComponentConfig;

class ComponentConfigRepository extends AbstractCrudRepository
{

    public function __construct()
    {
        parent::setModel(new ComponentConfig());
    }

}
