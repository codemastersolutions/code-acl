<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use Closure;
use CodeMaster\CodeAcl\Contracts\Permission;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Ramsey\Uuid\Uuid;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class PermissionDeleteMutation extends Mutation
{
    protected $attributes = [
        'name' => 'PermissionDelete',
        'description' => 'Exclui uma permissão'
    ];

    public function type(): Type
    {
        return GraphQL::type('Permission');
    }

    public function args(): array
    {
        return [
            'idOrSlug' => [
                'type' => Type::nonNull(Type::string())
            ],
            'name' => [
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'idOrSlug' => ['required'],
            'name' => ['required'],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $model = app(Permission::class);

        if (isset($args['idOrSlug'])) {
            if (Uuid::isValid($args['idOrSlug'])) {
                $permission = $model::findById($args['idOrSlug']);
            }
        }

        if (isset($args['idOrSlug'])) {
            $permission = $model::findBySlug($args['idOrSlug']);
        }

        $permission->name = $args['name'];
        $permission->save();
        $permission->refresh();

        return $permission;
    }
}
