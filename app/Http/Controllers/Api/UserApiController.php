<?php

namespace Nanicas\LegacyLaravelToolkit\Http\Controllers\Api;

use Nanicas\LegacyLaravelToolkit\Services\Api\UserApiService;
use Nanicas\LegacyLaravelToolkit\Http\Controllers\Api\AbstractApiController;
use Zevitagem\LegoAuth\Helpers\ApiState;
use Zevitagem\LegoAuth\Controllers\Api\AbstractApiController;
use Zevitagem\LegoAuth\Services\Api\UserApiService;

class UserApiController extends AbstractApiController
{
    public function __construct(UserApiService $userService)
    {
        $this->setService($userService);
    }

    public function teste()
    {
        return [
            ApiState::all()
        ];
    }
}