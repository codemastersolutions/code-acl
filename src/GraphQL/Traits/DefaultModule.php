<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use CodeMaster\CodeAcl\Contracts\Module;

trait DefaultModule
{
    public function __construct()
    {
        $this->model = app(Module::class);
    }
}
