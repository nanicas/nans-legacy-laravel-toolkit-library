<?php

namespace Zevitagem\LaravelToolkit\Services;

use Zevitagem\LaravelToolkit\Services\AbstractService;
use Zevitagem\LegoAuth\Services\UserService as ExternalUserService;
use Zevitagem\LegoAuth\Helpers\Helper as ExternalHelper;
use Zevitagem\LegoAuth\Services\SessionService;
use Zevitagem\LaravelToolkit\Repositories\UserRepository;

class UserService extends AbstractService
{
    public function __construct(
        UserRepository $repository,
        ExternalUserService $externalUserService
    )
    {
        parent::setRepository($repository);

        $this->setDependencie('external_user_service', $externalUserService);
    }

    public function find(int $id)
    {
        $sessionData = SessionService::getCurrentData();

        $response = $this->dependencies['external_user_service']->findInUrl(
            $id,
            $sessionData['authenticator']['internal_api_url_packaged'],
            ExternalHelper::getToken()
        );

        if ($response['status'] == false) {
            return null;
        }

        return $this->getRepository()->getModel()->hydrate([$response['response']])->first();
    }
}