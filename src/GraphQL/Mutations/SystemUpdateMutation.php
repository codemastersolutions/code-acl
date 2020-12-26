<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultSystem;
use CodeMaster\CodeAcl\GraphQL\Traits\UpdateMutation;
use Rebing\GraphQL\Support\Mutation;

class SystemUpdateMutation extends Mutation
{
    use DefaultSystem, UpdateMutation;

    protected $attributes = [
        'name' => 'SystemUpdateMutation',
        'description' => 'Atualiza um sistema no banco de dados'
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['name']['description'] = 'Nome do sistema a ser atualizado';
        $args['idOrSlug']['description'] = 'Id ou Slug do sistema a ser atualizado';
        return $args;
    }
}
