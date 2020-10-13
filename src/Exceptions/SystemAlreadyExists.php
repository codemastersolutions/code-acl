<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class SystemAlreadyExists extends InvalidArgumentException
{
    public static function create(string $systemName)
    {
        return new static("System `{$systemName}` already exists.");
    }
}
