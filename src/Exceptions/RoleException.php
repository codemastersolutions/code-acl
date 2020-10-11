<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class RoleException extends InvalidArgumentException
{
    public static function create(string $roleName, $exception)
    {
        return new static(
            "Failure to create role with name `{$roleName}`. Error: {$exception->getCode()} - {$exception->getMessage()}"
        );
    }
}
