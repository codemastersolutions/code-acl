<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Types;

use CodeMaster\CodeAcl\Contracts\Role;
use CodeMaster\CodeAcl\GraphQL\Traits\BaseType;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RoleType extends GraphQLType
{
    use BaseType;

    protected $attributes = [
        'name' => 'RoleType',
        'description' => 'Tipo para um papel',
        'model' => Role::class
    ];

    public function fields(): array
    {
        $fields = $this->getBaseFields();

        unset($fields['email']);
        unset($fields['modules']);
        unset($fields['roles']);
        unset($fields['systems']);

        return $fields;
    }
}
