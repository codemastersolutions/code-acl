<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class PermissionAlreadyExists extends InvalidArgumentException
{
    public static function create(string $permissionName)
    {
        return new static("Permission `{$permissionName}` already exists.");
    }
}
