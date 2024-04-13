<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories;

use Nanicas\LegacyLaravelToolkit\Repositories\AbstractCrudRepository;
use Nanicas\LegacyLaravelToolkit\Models\User;

class UserRepository extends AbstractCrudRepository
{
    public function __construct()
    {
        parent::setModel(new User());
    }
}
