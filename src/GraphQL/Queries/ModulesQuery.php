<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultModule;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsQuery;
use Rebing\GraphQL\Support\Query;

class ModulesQuery extends Query
{
    use DefaultModule, ItemsQuery;

    protected $attributes = [
        'name' => 'ModulesQuery',
        'description' => 'Retorna uma coleção de módulos'
    ];
}
