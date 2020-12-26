<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\CreateMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultModule;
use Rebing\GraphQL\Support\Mutation;

class ModuleCreateMutation extends Mutation
{
    use DefaultModule, CreateMutation;

    protected $attributes = [
        'name' => 'ModuleCreateMutation',
        'description' => 'Persiste um módulo no banco de dados'
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['name']['description'] = 'Nome do módulo a ser persistido';
        return $args;
    }
}
