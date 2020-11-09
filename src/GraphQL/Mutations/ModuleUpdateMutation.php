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
        'description' => 'Atualiza um módulo'
    ];
}
