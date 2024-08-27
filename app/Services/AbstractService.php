<?php

namespace Nanicas\LegacyLaravelToolkit\Services;

use Nanicas\LegacyLaravelToolkit\Handlers\AbstractHandler;
use Nanicas\LegacyLaravelToolkit\Validators\AbstractValidator;
use Nanicas\LegacyLaravelToolkit\Validators\AbstractAPIValidator;
use Nanicas\LegacyLaravelToolkit\Exceptions\ValidatorException;
use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithDependencie;
use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithRequest;
use Nanicas\LegacyLaravelToolkit\Traits\Configurable;

abstract class AbstractService
{
    use AvailabilityWithDependencie,
        AvailabilityWithRequest,
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

    public function setValidator(
        AbstractValidator|AbstractAPIValidator $validator
    ) {
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

        $request = $this->getRequest();
        if (is_object($request)) {
            $handler->setRequest($request);
        }

        $handler->setData($data);
        $handler->run($method);
    }

    public function validate(array $data, string $method)
    {
        if (empty($validator = $this->getValidator())) {
            return;
        }

        $validator->setData($data);

        $request = $this->getConfigIndex('request');
        if ($request) {
            $validator->setRequest($request);
        }

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
