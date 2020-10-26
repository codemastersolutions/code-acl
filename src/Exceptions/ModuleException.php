<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class ModuleException extends InvalidArgumentException
{
    public static function create(string $moduleName, $exception)
    {
        return new static(
            "Failure to create module with name `{$moduleName}`. Error: {$exception->getCode()} - {$exception->getMessage()}"
        );
    }
}
