<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use Closure;
use CodeMaster\CodeAcl\Contracts\Module;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ModulesPaginateQuery extends Query
{
    protected $attributes = [
        'name' => 'ModulesPaginateQuery',
        'description' => 'Retorna um coleção de módulos com paginação'
    ];

    public function type(): Type
    {
        return GraphQL::paginate('Module');
    }

    public function args(): array
    {
        return [
            'limit' => [
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

        $model = app(Module::class);

        return $model::select($fields->getSelect())
                ->paginate($args['limit'] ?? $model->perPage, ['*'], 'page', $args['page'] ?? 1);
    }
}
