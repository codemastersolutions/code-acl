<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\CreateMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultPermission;
use Rebing\GraphQL\Support\Mutation;

class PermissionCreateMutation extends Mutation
{
    use DefaultPermission, CreateMutation;

    protected $attributes = [
        'name' => 'PermissionCreateMutation',
        'description' => 'Insere uma permissão'
    ];
}
