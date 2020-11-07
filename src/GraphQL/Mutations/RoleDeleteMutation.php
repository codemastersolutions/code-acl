<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use Closure;
use CodeMaster\CodeAcl\Contracts\Role;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Ramsey\Uuid\Uuid;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class RoleDeleteMutation extends Mutation
{
    protected $attributes = [
        'name' => 'RoleDelete',
        'description' => 'Exclui um papel'
    ];

    public function type(): Type
    {
        return GraphQL::type('Role');
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
        $model = app(Role::class);

        if (isset($args['idOrSlug'])) {
            if (Uuid::isValid($args['idOrSlug'])) {
                $role = $model::findById($args['idOrSlug']);
            }
        }

        if (isset($args['idOrSlug'])) {
            $role = $model::findBySlug($args['idOrSlug']);
        }

        $role->name = $args['name'];
        $role->save();
        $role->refresh();

        return $role;
    }
}
