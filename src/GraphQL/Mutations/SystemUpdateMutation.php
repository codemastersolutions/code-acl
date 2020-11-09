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
        'description' => 'Atualiza um sistema'
    ];
}
