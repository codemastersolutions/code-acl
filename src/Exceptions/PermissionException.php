<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class PermissionException extends InvalidArgumentException
{
    public static function create(string $permissionName, $exception)
    {
        return new static(
            "Failure to create permission with name `{$permissionName}`. Error: {$exception->getCode()} - {$exception->getMessage()}"
        );
    }
}
