<?php

namespace Zevitagem\LaravelToolkit\Repositories\Config;

use Zevitagem\LaravelToolkit\Repositories\AbstractCrudRepository;
use Zevitagem\LaravelToolkit\Models\Config\CategoryConfig;

class CategoryConfigRepository extends AbstractCrudRepository
{
    public function __construct()
    {
        parent::setModel(new CategoryConfig());
    }
}
