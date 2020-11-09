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
        'description' => 'Exclui um módulo'
    ];
}
