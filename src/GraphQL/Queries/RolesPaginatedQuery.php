<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultRole;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsPaginatedQuery;
use Rebing\GraphQL\Support\Query;

class RolesPaginatedQuery extends Query
{
    use DefaultRole, ItemsPaginatedQuery;

    protected $attributes = [
        'name' => 'RolesPaginatedQuery',
        'description' => 'Retorna um coleção de papéis com paginação'
    ];
}
