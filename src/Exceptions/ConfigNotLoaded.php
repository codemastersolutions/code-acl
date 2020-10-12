<?php

namespace CodeMaster\CodeAcl\Exceptions;

use InvalidArgumentException;

class ConfigNotLoaded extends InvalidArgumentException
{
    public static function config($config)
    {
        return new static(
            "Error: {$config} not loaded. Run [php artisan config:clear] and try again."
        );
    }
}
