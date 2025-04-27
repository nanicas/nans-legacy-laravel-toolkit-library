<?php

namespace Nanicas\LegacyLaravelToolkit\Exceptions;

use Nanicas\LegacyLaravelToolkit\Helpers\Helper as InternalHelper;

class_alias(InternalHelper::readTemplateConfig()['helpers']['global'], __NAMESPACE__ . '\CExxHelperAlias');

class CrudException extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable $previous
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        \Throwable $previous = null
    ) {
        $message = CExxHelperAlias::view(
            'components.validator-messages',
            ['messages' => [$message]],
            true
        )->render();

        parent::__construct($message, $code, $previous);
    }
}
