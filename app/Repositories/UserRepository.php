<?php

namespace Zevitagem\LaravelToolkit\Repositories;

use Zevitagem\LaravelToolkit\Repositories\AbstractCrudRepository;
use Zevitagem\LaravelToolkit\Models\User;

class UserRepository extends AbstractCrudRepository
{
    public function __construct()
    {
        parent::setModel(new User());
    }
}
