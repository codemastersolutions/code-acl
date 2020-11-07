<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use Closure;
use CodeMaster\CodeAcl\Contracts\Permission;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class PermissionCreateMutation extends Mutation
{
    protected $attributes = [
        'name' => 'PermissionCreate',
        'description' => 'Insere uma permissão'
    ];

    public function type(): Type
    {
        return GraphQL::type('Permission');
    }

    public function args(): array
    {
        return [
            'name' => [
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'name' => ['required'],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $model = app(Permission::class);

        return $model::findOrCreate($args['name']);
    }
}
