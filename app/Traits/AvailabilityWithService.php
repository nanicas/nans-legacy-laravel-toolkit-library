<?php

namespace Zevitagem\LaravelToolkit\Traits;

//use Zevitagem\LaravelToolkit\Services\AbstractService;

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
