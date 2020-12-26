<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Types;

use CodeMaster\CodeAcl\Contracts\Permission;
use CodeMaster\CodeAcl\GraphQL\Traits\BaseType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PermissionType extends GraphQLType
{
    use BaseType;

    protected $attributes = [
        'name' => 'PermissionType',
        'description' => 'Tipo para uma permissão',
        'model' => Permission::class
    ];

    public function fields(): array
    {
        $fields = $this->getBaseFields();

        unset($fields['email']);
        unset($fields['modules']);
        unset($fields['permissions']);
        unset($fields['systems']);

        return $fields;
    }
}
