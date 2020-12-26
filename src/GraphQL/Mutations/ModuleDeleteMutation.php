<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultModule;
use CodeMaster\CodeAcl\GraphQL\Traits\DeleteMutation;
use Rebing\GraphQL\Support\Mutation;

class ModuleDeleteMutation extends Mutation
{
    use DefaultModule, DeleteMutation;

    protected $attributes = [
        'name' => 'ModuleDeleteMutation',
        'description' => 'Exclui um módulo do banco de dados'
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['idOrSlug']['description'] = 'ID or Slug do módulo a ser excluído';
        return $args;
    }
}
