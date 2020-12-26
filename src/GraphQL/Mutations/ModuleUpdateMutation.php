<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultModule;
use CodeMaster\CodeAcl\GraphQL\Traits\UpdateMutation;
use Rebing\GraphQL\Support\Mutation;

class ModuleUpdateMutation extends Mutation
{
    use DefaultModule, UpdateMutation;

    protected $attributes = [
        'name' => 'ModuleUpdateMutation',
        'description' => 'Atualiza um módulo no banco de dados'
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['name']['description'] = 'Nome do módulo a ser atualizado';
        $args['idOrSlug']['description'] = 'Id ou Slug do módulo a ser atualizado';
        return $args;
    }
}
