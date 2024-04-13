<?php

namespace Zevitagem\LaravelToolkit\Services;

use Zevitagem\LaravelToolkit\Services\AbstractService;
use Zevitagem\LegoAuth\Services\ApplicationService;
use Zevitagem\LegoAuth\Filters\ApplicationRemoverItself;

class HomeService extends AbstractService
{
    public function getIndexData(array $data = [])
    {
        $service      = new ApplicationService(new ApplicationRemoverItself());
        $applications = $service->getApplicationsToShareSession();

        return compact('applications');
    }
}