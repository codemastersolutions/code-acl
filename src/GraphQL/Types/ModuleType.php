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
