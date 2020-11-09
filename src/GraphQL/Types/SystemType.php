<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Types;

use CodeMaster\CodeAcl\Contracts\System;
use CodeMaster\CodeAcl\GraphQL\Traits\BaseType;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SystemType extends GraphQLType
{
    use BaseType;

    protected $attributes = [
        'name' => 'SystemType',
        'description' => 'Tipo para um sistema',
        'model' => System::class
    ];
}
