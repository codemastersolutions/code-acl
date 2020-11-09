<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\CreateMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultRole;
use Rebing\GraphQL\Support\Mutation;

class RoleCreateMutation extends Mutation
{
    use DefaultRole, CreateMutation;

    protected $attributes = [
        'name' => 'RoleCreateMutation',
        'description' => 'Insere um papel'
    ];
}
