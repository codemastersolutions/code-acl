<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Types;

use CodeMaster\CodeAcl\Contracts\Role;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RoleType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Role',
        'description' => 'Tipo para um papel',
        'model' => Role::class
    ];

    public function fields(): array
    {
        return [
            'createdAt' => [
                'type' => Type::string(),
                'description' => 'Data de criação do papel',
                'alias' => 'created_at',
            ],
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Identificador único do papel no formato UUID',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Nome do papel',
            ],
            'updatedAt' => [
                'type' => Type::string(),
                'description' => 'Data da última de atualização do papel',
                'alias' => 'updated_at',
            ],
            'users' => [
                'type' => Type::listOf(GraphQL::type('User')),
                'description' => 'Lista de usuários vinculados ao papel',
            ],
        ];
    }
}
