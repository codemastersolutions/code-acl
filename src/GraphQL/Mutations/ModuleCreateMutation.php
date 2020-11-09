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
        'description' => 'Insere um módulo'
    ];
}
