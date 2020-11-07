<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use Closure;
use CodeMaster\CodeAcl\Contracts\Permission;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class PermissionsQuery extends Query
{
    protected $attributes = [
        'name' => 'permissions',
        'description' => 'Retorna uma coleção de Permissões'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Permission'));
    }

    public function args(): array
    {
        return [];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();

        $model = app(Permission::class);

        return $model::select($fields->getSelect())
                ->with($fields->getRelations())
                ->orderBy('created_at', 'desc')
                ->get();
    }
}
