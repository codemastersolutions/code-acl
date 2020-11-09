<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultPermission;
use CodeMaster\CodeAcl\GraphQL\Traits\DeleteMutation;
use Rebing\GraphQL\Support\Mutation;

class PermissionDeleteMutation extends Mutation
{
    use DefaultPermission, DeleteMutation;

    protected $attributes = [
        'name' => 'PermissionDeleteMutation',
        'description' => 'Exclui uma permissão'
    ];
}
