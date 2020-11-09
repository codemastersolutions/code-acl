<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use CodeMaster\CodeAcl\Contracts\Role;

trait DefaultRole
{
    public function __construct()
    {
        $this->model = app(Role::class);
    }
}
