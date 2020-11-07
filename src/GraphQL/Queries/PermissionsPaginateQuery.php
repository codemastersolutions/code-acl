<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use Closure;
use CodeMaster\CodeAcl\Contracts\Permission;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class PermissionsPaginateQuery extends Query
{
    protected $attributes = [
        'name' => 'PermissionsPaginate',
        'description' => 'Retorna um coleção de permissões com paginação'
    ];

    public function type(): Type
    {
        return GraphQL::paginate('Permission');
    }

    public function args(): array
    {
        return [
            'per_page' => [
                'type' => Type::int(),
                'description' => 'Máximo de registros por página'
            ],
            'page' => [
                'type' => Type::int(),
                'description' => 'Página a ser consultada'
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();

        $model = app(Permission::class);

        return $model::select($fields->getSelect())
                ->with($fields->getRelations())
                ->orderBy('created_at', 'desc')
                ->paginate($args['per_page'] ?? $model->perPage, ['*'], 'page', $args['page'] ?? 1);
    }
}
