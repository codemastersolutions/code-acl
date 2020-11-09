<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Types;

use CodeMaster\CodeAcl\GraphQL\Traits\BaseType;
use CodeMaster\CodeAcl\Models\User;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    use BaseType;

    protected $attributes = [
        'name' => 'UserType',
        'description' => 'Tipo para um usuário',
        'model' => User::class
    ];

    public function fields(): array
    {
        $fields = $this->getBaseFields();

        unset($fields['users']);

        return $fields;
    }
}
