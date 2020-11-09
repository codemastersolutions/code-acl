<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

trait BaseType
{
    public function fields(): array
    {
        return $this->getBaseFields();
    }

    public function getBaseFields(): array
    {
        return [
            'createdAt' => [
                'type' => Type::string(),
                'description' => 'Data de criação',
                'alias' => 'created_at',
            ],
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Identificador único no formato UUID',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Nome',
            ],
            'slug' => [
                'type' => Type::string(),
                'description' => 'Identificador único no formato slug',
            ],
            'updatedAt' => [
                'type' => Type::string(),
                'description' => 'Data da última de atualização',
                'alias' => 'updated_at',
            ],
            'users' => [
                'type' => Type::listOf(GraphQL::type('User')),
                'description' => 'Lista de usuários vinculados',
            ],
        ];
    }
}
