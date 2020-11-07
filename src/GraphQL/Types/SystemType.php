<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Types;

use CodeMaster\CodeAcl\Contracts\System;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SystemType extends GraphQLType
{
    protected $attributes = [
        'name' => 'SystemType',
        'description' => 'Tipo para um sistema',
        'model' => System::class
    ];

    public function fields(): array
    {
        return [
            'createdAt' => [
                'type' => Type::string(),
                'description' => 'Data de criação do sistema',
                'alias' => 'created_at',
            ],
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Identificador único do sistema no formato UUID',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Nome do sistema',
            ],
            'updatedAt' => [
                'type' => Type::string(),
                'description' => 'Data da última de atualização do sistema',
                'alias' => 'updated_at',
            ],
            'users' => [
                'type' => Type::listOf(GraphQL::type('User')),
                'description' => 'Lista de usuários vinculados ao sistema',
            ],
        ];
    }
}
