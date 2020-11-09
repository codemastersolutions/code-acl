<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultRole;
use CodeMaster\CodeAcl\GraphQL\Traits\UpdateMutation;
use Rebing\GraphQL\Support\Mutation;

class RoleUpdateMutation extends Mutation
{
    use DefaultRole, UpdateMutation;

    protected $attributes = [
        'name' => 'RoleUpdateMutation',
        'description' => 'Atualiza um papel'
    ];
}
