<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use Closure;
use CodeMaster\CodeAcl\Contracts\Permission;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Ramsey\Uuid\Uuid;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class PermissionQuery extends Query
{
    protected $attributes = [
        'name' => 'permission',
        'description' => 'Retorn uma permissão'
    ];

    public function type(): Type
    {
        return GraphQL::type('Permission');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::string(),
                'description' => 'Identificador único da permissão no formato UUID a ser consultada'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Nome da permissão a ser consultada'
            ],
            'slug' => [
                'type' => Type::string(),
                'description' => 'Identificador único da permissão no formato slug'
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        try {
            $model = app(Permission::class);

            if (isset($args['id'])) {
                if (Uuid::isValid($args['id'])) {
                    return $model::findById($args['id']);
                }

                return [];
            }

            if (isset($args['slug'])) {
                return $model::findBySlug($args['slug']);
            }

            if (isset($args['name'])) {
                return $model::findByName($args['name']);
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
