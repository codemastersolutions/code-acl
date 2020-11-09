<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultRole;
use CodeMaster\CodeAcl\GraphQL\Traits\DeleteMutation;
use Rebing\GraphQL\Support\Mutation;

class RoleDeleteMutation extends Mutation
{
    use DefaultRole, DeleteMutation;

    protected $attributes = [
        'name' => 'RoleDeleteMutation',
        'description' => 'Exclui um papel'
    ];
}
