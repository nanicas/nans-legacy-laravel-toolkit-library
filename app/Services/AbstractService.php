<?php

namespace Nanicas\LegacyLaravelToolkit\Services;

use Nanicas\LegacyLaravelToolkit\Handlers\AbstractHandler;
use Nanicas\LegacyLaravelToolkit\Validators\AbstractValidator;
use Nanicas\LegacyLaravelToolkit\Exceptions\ValidatorException;
use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithDependencie;
use Nanicas\LegacyLaravelToolkit\Traits\Configurable;

abstract class AbstractService
{
    use AvailabilityWithDependencie,
        Configurable;

    protected $handler;
    protected $validator;
    protected $repository;

    public function getRepository()
    {
        return $this->repository;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getValidator()
    {
        return $this->validator;
    }

    public function setValidator(AbstractValidator $validator)
    {
        $this->validator = $validator;
    }

    public function setHandler(AbstractHandler $handler)
    {
        $this->handler = $handler;
    }

    public function handle(array &$data, string $method)
    {
        if (empty($handler = $this->getHandler())) {
            return;
        }

        $handler->setRequest($this->getConfigIndex('request'));
        $handler->setData($data);
        $handler->run($method);
    }

    public function validate(array $data, string $method)
    {
        if (empty($validator = $this->getValidator())) {
            return;
        }

        $validator->setData($data);
        $validator->setRequest($this->getConfigIndex('request'));

        if ($validator->run($method) === false) {
            $exception = new ValidatorException($validator->translate());
            $exception->setErrors($validator->getErrors());

            throw $exception;
        }

        return true;
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }
}
