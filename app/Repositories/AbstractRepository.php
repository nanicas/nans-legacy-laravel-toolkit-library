<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories;

abstract class AbstractRepository
{
    protected object $model;

    public function getModel(): object
    {
        return $this->model;
    }

    public function getModelClass(): string
    {
        return get_class($this->model);
    }

    protected function setModel(object $model): void
    {
        $this->model = $model;
    }
}
