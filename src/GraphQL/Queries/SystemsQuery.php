<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultSystem;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsQuery;
use Rebing\GraphQL\Support\Query;

class SystemsQuery extends Query
{
    use DefaultSystem, ItemsQuery;

    protected $attributes = [
        'name' => 'SystemsQuery',
        'description' => 'Retorna uma coleção de sistemas'
    ];
}
