<?php

namespace Nanicas\LegacyLaravelToolkit\Traits;

trait AvailabilityWithService
{
    private object $service;

    public function setService(object $service)
    {
        $this->service = $service;
    }

    public function getService()
    {
        return $this->service;
    }
}
