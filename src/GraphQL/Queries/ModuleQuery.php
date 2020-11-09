<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultModule;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemQuery;
use Rebing\GraphQL\Support\Query;

class ModuleQuery extends Query {
    use DefaultModule, ItemQuery;

    protected $attributes = [
        'name' => 'ModuleQuery',
        'description' => 'Retorna um módulo'
    ];
}
