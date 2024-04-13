<?php

namespace Zevitagem\LaravelToolkit\Repositories\Config;

use Zevitagem\LaravelToolkit\Repositories\AbstractCrudRepository;
use Zevitagem\LaravelToolkit\Models\Config\DataConfig;

class DataConfigRepository extends AbstractCrudRepository
{
    public function __construct()
    {
        parent::setModel(new DataConfig());
    }
}
