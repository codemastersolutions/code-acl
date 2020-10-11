<?php

namespace CodeMaster\CodeAcl\Exceptions;

use Exception;

class UserModelNotFound
{
    public function __construct()
    {
        throw new Exception("Error: User model couldn't be loaded.");
    }
}
