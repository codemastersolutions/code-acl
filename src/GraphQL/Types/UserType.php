<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Types;

use CodeMaster\CodeAcl\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'Tipo para um usuário',
        'model' => User::class
    ];

    public function fields(): array
    {
        return [
            'createdAt' => [
                'type' => Type::string(),
                'description' => 'Data de criação do usuário',
                'alias' => 'created_at',
            ],
            'emailVerifiedAt' => [
                'type' => Type::string(),
                'description' => 'Data da última de atualização do usuário',
                'alias' => 'email_verified_at',
            ],
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Identificador único do usuário no formato UUID'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Nome do usuário'
            ],
            'updatedAt' => [
                'type' => Type::string(),
                'description' => 'Data da última de atualização do usuário',
                'alias' => 'updated_at',
            ],
        ];
    }
}
