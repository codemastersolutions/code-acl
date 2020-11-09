<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultSystem;
use CodeMaster\CodeAcl\GraphQL\Traits\DeleteMutation;
use Rebing\GraphQL\Support\Mutation;

class SystemDeleteMutation extends Mutation
{
    use DefaultSystem, DeleteMutation;

    protected $attributes = [
        'name' => 'SystemDeleteMutation',
        'description' => 'Exclui um sistema'
    ];
}
