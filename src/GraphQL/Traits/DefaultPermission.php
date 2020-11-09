<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use CodeMaster\CodeAcl\Contracts\Permission;

trait DefaultPermission
{
    public function __construct()
    {
        $this->model = app(Permission::class);
    }
}
