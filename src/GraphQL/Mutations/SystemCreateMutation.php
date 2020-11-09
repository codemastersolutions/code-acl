<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\CreateMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultSystem;
use Rebing\GraphQL\Support\Mutation;

class SystemCreateMutation extends Mutation
{
    use DefaultSystem, CreateMutation;

    protected $attributes = [
        'name' => 'SystemCreateMutation',
        'description' => 'Insere um sistema'
    ];
}
