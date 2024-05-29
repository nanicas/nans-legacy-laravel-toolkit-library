<?php

namespace Nanicas\LegacyLaravelToolkit\Exceptions;

use Nanicas\LegacyLaravelToolkit\Traits\Configurable;
use Illuminate\Validation\Validator;
use Exception;

class ValidatorException extends Exception
{
    use Configurable;

    protected array $errors;
    protected Validator $validator;

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return Validator
     */
    public function getValidator(): Validator
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
     * @param Validator $validator
     * @return void
     */
    public function setValidator(Validator $validator): void
    {
        $this->validator = $validator;
    }

    /**
     * @param array $errors
     * @param Validator|null $validator
     * @param array $config
     * @return self
     */
    public static function new(
        array $errors,
        ?Validator $validator = null,
        array $config = []
    ): self {
        $self = new self();
        $self->setErrors($errors);
        $self->configure($config);

        if ($validator) {
            $self->setValidator($validator);
        }

        return $self;
    }
}
