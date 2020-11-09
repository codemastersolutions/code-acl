<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultRole;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsQuery;
use Rebing\GraphQL\Support\Query;

class RolesQuery extends Query
{
    use DefaultRole, ItemsQuery;

    protected $attributes = [
        'name' => 'RolesQuery',
        'description' => 'Retorna uma coleção de papéis'
    ];
}
