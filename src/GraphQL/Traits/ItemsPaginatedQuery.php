<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

trait ItemsPaginatedQuery
{
    use BaseQuery;

    public function type(): Type
    {
        return GraphQL::paginate(class_basename(\get_class($this->model)));
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

        return $this->model::select($fields->getSelect())
                ->with($fields->getRelations())
                ->orderBy('created_at', 'desc')
                ->paginate($args['per_page'] ?? $this->model->perPage, ['*'], 'page', $args['page'] ?? 1);
    }
}
