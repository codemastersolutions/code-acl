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
        'description' => 'Atualiza um papel no banco de dados'
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['name']['description'] = 'Nome do papel a ser atualizado';
        $args['idOrSlug']['description'] = 'Id ou Slug do papel a ser atualizado';
        return $args;
    }
}
