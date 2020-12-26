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
            'email' => [
                'type' => Type::string(),
                'description' => 'E-mail'
            ],
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Identificador único no formato UUID',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Nome',
            ],
            'modules' => [
                'type' => Type::listOf(GraphQL::type('Module')),
                'description' => 'Lista de módulos vinculados',
            ],
            'permissions' => [
                'type' => Type::listOf(GraphQL::type('Permission')),
                'description' => 'Lista de permissões vinculados',
            ],
            'roles' => [
                'type' => Type::listOf(GraphQL::type('Role')),
                'description' => 'Lista de papéis vinculados',
            ],
            'slug' => [
                'type' => Type::string(),
                'description' => 'Identificador único no formato slug',
            ],
            'systems' => [
                'type' => Type::listOf(GraphQL::type('System')),
                'description' => 'Lista de sistemas vinculados',
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

    // protected function resolveEmailField($root, $args)
    // {
    //     if (isset($root->email)) {
    //         return strtolower($root->email);
    //     }
    // }
}
