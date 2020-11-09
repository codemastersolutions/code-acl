<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultPermission;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsQuery;
use Rebing\GraphQL\Support\Query;

class PermissionsQuery extends Query
{
    use DefaultPermission, ItemsQuery;

    protected $attributes = [
        'name' => 'PermissionsQuery',
        'description' => 'Retorna uma coleção de permissões'
    ];
}
