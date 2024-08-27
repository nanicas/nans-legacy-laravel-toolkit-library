<?php

namespace Nanicas\LegacyLaravelToolkit\Traits;

trait AvailabilityWithRequest
{
    /**
     * @var object|null
     */
    protected object|null $request;

    /**
     * @param object $request
     */
    public function setRequest(object $request)
    {
        $this->request = $request;
    }

    /**
     * @return object|null
     */
    public function getRequest(): object|null
    {
        return $this->request;
    }
}
