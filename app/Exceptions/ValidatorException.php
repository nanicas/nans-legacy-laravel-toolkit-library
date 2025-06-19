<?php

namespace Nanicas\LegacyLaravelToolkit\Exceptions;

use Nanicas\LegacyLaravelToolkit\Traits\Configurable;
use Exception;

class ValidatorException extends Exception
{
    use Configurable;

    protected object $validator;

    protected array $errors;

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return object
     */
    public function getValidator(): object
    {
        return $this->validator;
    }

    /**
     * @param array $errors
     * @return void
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @param object $validator
     * @return void
     */
    public function setValidator(object $validator): void
    {
        $this->validator = $validator;
    }

    /**
     * @param array $errors
     * @param object|null $validator
     * @param array $config
     * @return self
     */
    public static function new(
        array $errors,
        ?object $validator = null,
        array $config = []
    ): self {
        $self = new self();
        $self->setErrors($errors);

        if ($config) {
            $self->configure($config);
        }

        if ($validator) {
            $self->setValidator($validator);
        }

        return $self;
    }
}
