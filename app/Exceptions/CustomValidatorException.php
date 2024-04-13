<?php

namespace Nanicas\LegacyLaravelToolkit\Exceptions;

use App\Helpers\Helper;

class CustomValidatorException extends \Exception
{
    private bool $translated = false;

    protected function translated(bool $value)
    {
        $this->translated = $value;
    }

    public function __construct(
        string $message = "", int $code = 0, \Throwable $previous = null
    )
    {
        $packaged = true;

        if (!$this->translated) {
            $message = Helper::view(
                'components.validator-messages', ['messages' => [$message]], $packaged
            )->render();
        }

        parent::__construct($message, $code, $previous);
    }
}
