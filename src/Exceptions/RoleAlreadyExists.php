<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class RoleAlreadyExists extends InvalidArgumentException
{
    public static function create(string $roleName)
    {
        return new static("Role `{$roleName}` already exists.");
    }
}
