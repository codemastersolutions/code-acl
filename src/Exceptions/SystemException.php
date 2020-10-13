<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class SystemException extends InvalidArgumentException
{
    public static function create(string $systemName, $exception)
    {
        return new static(
            "Failure to create system with name `{$systemName}`. Error: {$exception->getCode()} - {$exception->getMessage()}"
        );
    }
}
