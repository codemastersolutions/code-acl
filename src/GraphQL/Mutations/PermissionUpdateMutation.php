<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultPermission;
use CodeMaster\CodeAcl\GraphQL\Traits\UpdateMutation;
use Rebing\GraphQL\Support\Mutation;

class PermissionUpdateMutation extends Mutation
{
    use DefaultPermission, UpdateMutation;

    protected $attributes = [
        'name' => 'PermissionUpdateMutation',
        'description' => 'Atualiza uma permissão no banco de dados'
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['name']['description'] = 'Nome da permissão a ser atualizada';
        $args['idOrSlug']['description'] = 'Id ou Slug da permissão a ser atualizada';
        return $args;
    }
}
