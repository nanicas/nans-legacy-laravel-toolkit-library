<?php

namespace Nanicas\LegacyLaravelToolkit\Validators;

use InvalidArgumentException;
use Nanicas\LegacyLaravelToolkit\Traits\Configurable;
use Illuminate\Validation\Factory as ValidatorFactory;
use Nanicas\LegacyLaravelToolkit\Exceptions\ValidatorException;
use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithRequest;

abstract class AbstractAPIValidator
{
    use Configurable, AvailabilityWithRequest;

    /**
     * @var array
     */
    protected array $data;

    /**
     * @var array
     */
    protected array $errorKeys = [
        'test.0' => 'default',
    ];

    /**
     * @param ValidatorFactory $validator
     */
    public function __construct(
        protected ValidatorFactory $validator,
    ) {
    }

    /**
     * @param array $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param string $method
     * @throws ValidatorException
     * @return null|bool
     */
    public function run(string $method): null|bool
    {
        if (!method_exists($this, $method)) {
            return null;
        }

        $validator = $this->$method($this->data);

        if (!empty($errors = $validator->errors()->messages())) {
            throw ValidatorException::new(
                $errors,
                $validator,
                $this->getConfig()
            );
        }

        return true;
    }

    /**
     * @param string $function
     * @param int $index
     * @return string
     * @throws InvalidArgumentException
     */
    protected function getErrorKey(string $function, int $index = 0): string
    {
        if (!isset($this->errorKeys[$function . '.' . $index])) {
            throw new InvalidArgumentException('Error key not found');
        }

        return $this->errorKeys[$function . '.' . $index];
    }
}
