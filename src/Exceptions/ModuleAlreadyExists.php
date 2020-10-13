<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class ModuleAlreadyExists extends InvalidArgumentException
{
    public static function create(string $moduleName)
    {
        return new static("Module `{$moduleName}` already exists.");
    }
}
