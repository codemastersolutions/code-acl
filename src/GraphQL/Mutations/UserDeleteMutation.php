<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUser;
use CodeMaster\CodeAcl\GraphQL\Traits\DeleteMutation;
use Rebing\GraphQL\Support\Mutation;

class UserDeleteMutation extends Mutation
{
    use DefaultUser, DeleteMutation;

    protected $attributes = [
        'name' => 'UserDeleteMutation',
        'description' => 'Exclui um usuário do banco de dados'
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['idOrSlug']['description'] = 'ID or Slug do usuário a ser excluído';
        return $args;
    }
}
