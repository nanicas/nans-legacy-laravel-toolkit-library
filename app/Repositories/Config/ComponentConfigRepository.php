<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories\Config;

use Nanicas\LegacyLaravelToolkit\Repositories\AbstractCrudRepository;
use Nanicas\LegacyLaravelToolkit\Models\Config\ComponentConfig;

class ComponentConfigRepository extends AbstractCrudRepository
{

    public function __construct()
    {
        parent::setModel(new ComponentConfig());
    }

}
