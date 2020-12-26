<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;

trait BaseQuery
{
    protected $model;

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        if (isset(config('graphql.schemas.code_acl.middleware')['auth:sanctum'])) {
            return ! Auth::guest();
        }

        return true;
    }

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

        return $data;
    }
}
