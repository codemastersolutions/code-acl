<?php

namespace CodeMaster\CodeAcl\Exceptions;

use Exception;

class ConfigNotLoaded
{
    public function __construct()
    {
        throw new Exception("Error: config/code-acl.php not loaded. Run [php artisan config:clear] and try again.");
    }
}
