<?php

namespace Nanicas\LegacyLaravelToolkit\Traits;

//use Nanicas\LegacyLaravelToolkit\Services\AbstractService;

trait AvailabilityWithService
{
    private $service;

    //public function setService(AbstractService $service)
    public function setService($service)
    {
        $this->service = $service;
    }

    public function getService()
    {
        return $this->service;
    }
}
