<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Types;

use CodeMaster\CodeAcl\Contracts\Module;
use CodeMaster\CodeAcl\GraphQL\Traits\BaseType;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ModuleType extends GraphQLType
{
    use BaseType;

    protected $attributes = [
        'name' => 'ModuleType',
        'description' => 'Tipo para um módulo',
        'model' => Module::class
    ];
}
