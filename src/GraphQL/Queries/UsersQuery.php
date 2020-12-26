<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUser;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsQuery;
use Rebing\GraphQL\Support\Query;

class UsersQuery extends Query
{
    use DefaultUser, ItemsQuery;

    protected $attributes = [
        'name' => 'UsersQuery',
        'description' => 'Retorna uma coleção de usuários'
    ];
}
