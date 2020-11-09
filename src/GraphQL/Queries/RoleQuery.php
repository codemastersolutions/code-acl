<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultRole;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemQuery;
use Rebing\GraphQL\Support\Query;

class RoleQuery extends Query
{
    use DefaultRole, ItemQuery;

    protected $attributes = [
        'name' => 'RoleQuery',
        'description' => 'Retorna um papel'
    ];
}
