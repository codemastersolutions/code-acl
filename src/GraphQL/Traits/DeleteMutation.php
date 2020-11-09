<?php

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

trait DeleteMutation
{
    public function type(): Type
    {
        return Type::boolean();
    }

    public function args(): array
    {
        return [
            'idOrSlug' => [
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'idOrSlug' => ['required'],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return $this->delete($args);
    }
}
