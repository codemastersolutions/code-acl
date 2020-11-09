<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultModule;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsPaginatedQuery;
use Rebing\GraphQL\Support\Query;

class ModulesPaginatedQuery extends Query
{
    use DefaultModule, ItemsPaginatedQuery;

    protected $attributes = [
        'name' => 'ModulesPaginatedQuery',
        'description' => 'Retorna um coleção de módulos com paginação'
    ];
}
