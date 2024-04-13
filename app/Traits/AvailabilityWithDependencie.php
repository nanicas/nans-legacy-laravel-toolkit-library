<?php

namespace Nanicas\LegacyLaravelToolkit\Traits;

trait AvailabilityWithDependencie
{
    protected $dependencies = [];

    public function setDependencie(string $key, $object)
    {
        $this->dependencies[$key] = $object;
    }

    public function getDependencie(string $key)
    {
        return (isset($this->dependencies[$key])) ? $this->dependencies[$key] : null;
    }
}
