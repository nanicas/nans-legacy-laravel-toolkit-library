<?php

namespace Zevitagem\LaravelToolkit\Http\Controllers\Api;

use Zevitagem\LaravelToolkit\Services\Api\UserApiService;
use Zevitagem\LaravelToolkit\Http\Controllers\Api\AbstractApiController;
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