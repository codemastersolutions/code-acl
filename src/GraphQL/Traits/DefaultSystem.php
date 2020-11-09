<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use CodeMaster\CodeAcl\Contracts\System;

trait DefaultSystem
{
    public function __construct()
    {
        $this->model = app(System::class);
    }
}
