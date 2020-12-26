<?php

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

trait DeleteMutation
{
    use BaseMutation;

    public function type(): Type
    {
        return Type::boolean();
    }

    public function baseArgs(): array
    {
        return [
            'idOrSlug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Id ou Slug'
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
