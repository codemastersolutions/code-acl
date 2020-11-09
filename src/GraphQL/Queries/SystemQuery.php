<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultSystem;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemQuery;
use Rebing\GraphQL\Support\Query;

class SystemQuery extends Query
{
    use DefaultSystem, ItemQuery;

    protected $attributes = [
        'name' => 'SystemQuery',
        'description' => 'Retorna um sistema'
    ];
}
