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
        'description' => 'Exclui uma permissão do banco de dados'
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['idOrSlug']['description'] = 'ID or Slug da permissão a ser excluída';
        return $args;
    }
}
