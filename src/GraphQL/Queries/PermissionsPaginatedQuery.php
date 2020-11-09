<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultPermission;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsPaginatedQuery;
use Rebing\GraphQL\Support\Query;

class PermissionsPaginatedQuery extends Query
{
    use DefaultPermission, ItemsPaginatedQuery;

    protected $attributes = [
        'name' => 'PermissionsPaginatedQuery',
        'description' => 'Retorna um coleção de permissões com paginação'
    ];
}
