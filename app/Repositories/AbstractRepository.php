<?php

namespace Nanicas\LegacyLaravelToolkit\Repositories;

abstract class AbstractRepository
{
    protected $model;

    protected function setModel($model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getClassModel()
    {
        return get_class($this->model);
    }
}