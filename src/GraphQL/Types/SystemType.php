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

    public function fields(): array
    {
        $fields = $this->getBaseFields();

        unset($fields['email']);
        unset($fields['modules']);
        unset($fields['permissions']);
        unset($fields['roles']);
        unset($fields['systems']);

        return $fields;
    }
}
