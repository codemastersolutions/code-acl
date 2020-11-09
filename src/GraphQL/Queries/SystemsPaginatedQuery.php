<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultSystem;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsPaginatedQuery;
use Rebing\GraphQL\Support\Query;

class SystemsPaginatedQuery extends Query
{
    use DefaultSystem, ItemsPaginatedQuery;

    protected $attributes = [
        'name' => 'SystemsPaginatedQuery',
        'description' => 'Retorna um coleção de sistemas com paginação'
    ];
}
