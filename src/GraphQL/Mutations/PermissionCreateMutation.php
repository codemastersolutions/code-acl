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
        'description' => 'Persiste uma permissão no banco de dados'
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['name']['description'] = 'Nome da permissão a ser persistida';
        return $args;
    }
}
