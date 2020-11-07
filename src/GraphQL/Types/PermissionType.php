<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Types;

use CodeMaster\CodeAcl\Contracts\Permission;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PermissionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Permission',
        'description' => 'Tipo para uma permissão',
        'model' => Permission::class
    ];

    public function fields(): array
    {
        return [
            'createdAt' => [
                'type' => Type::string(),
                'description' => 'Data de criação da permissão',
                'alias' => 'created_at'
            ],
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Identificador único da permissão no formato UUID'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Nome da permissão'
            ],
            'roles' => [
                'type' => Type::listOf(GraphQL::type('Role')),
                'description' => 'Lista de papéis vinculados à permissão'
            ],
            'slug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Identificador único da permissão no formato slug'
            ],
            'updatedAt' => [
                'type' => Type::string(),
                'description' => 'Data da última de atualização da permissão',
                'alias' => 'updated_at'
            ],
            'users' => [
                'type' => Type::listOf(GraphQL::type('User')),
                'description' => 'Lista de usuários vinculados à permissão',
            ],
        ];
    }
}
