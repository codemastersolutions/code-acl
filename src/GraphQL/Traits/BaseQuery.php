<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

trait BaseQuery
{
    protected $model;

    public function type(): Type
    {
        return GraphQL::type(class_basename(\get_class($this->model)));
    }

    public function args(): array
    {
        return [];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();

        $data = $this->model::select($fields->getSelect())
                ->with($fields->getRelations())
                ->orderBy('created_at', 'desc')
                ->get();

                codelog('data', [$data]);

                return $data;
    }
}
