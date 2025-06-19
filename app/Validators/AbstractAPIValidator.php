<?php

namespace Nanicas\LegacyLaravelToolkit\Validators;

use Nanicas\LegacyLaravelToolkit\Traits\Configurable;
use Illuminate\Validation\Factory as ValidatorFactory;
use Nanicas\LegacyLaravelToolkit\Traits\AvailabilityWithRequest;

abstract class AbstractAPIValidator
{
    use Configurable, AvailabilityWithRequest;

    /**
     * @var array
     */
    protected array $data;

    /**
     * @param ValidatorFactory $validator
     */
    public function __construct(
        protected ValidatorFactory $validator,
    ) {}

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
     * @return null|bool
     */
    public function run(string $method): null|bool
    {
        if (!method_exists($this, $method)) {
            return null;
        }

        $validator = $this->$method($this->data);
        $this->configureIndex('validator_bagger', $validator);

        return empty($validator->errors()->messages());
    }
}
