<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultPermission;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemQuery;
use Rebing\GraphQL\Support\Query;

class PermissionQuery extends Query
{
    use DefaultPermission, ItemQuery;

    protected $attributes = [
        'name' => 'PermissionQuery',
        'description' => 'Retorn uma permissão'
    ];
}
