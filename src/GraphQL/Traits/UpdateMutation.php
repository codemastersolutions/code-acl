<?php

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Ramsey\Uuid\Uuid;
use Rebing\GraphQL\Support\Facades\GraphQL;

trait UpdateMutation
{
    use BaseMutation;

    public function type(): Type
    {
        return GraphQL::type(class_basename(\get_class($this->model)));
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
            'name' => ['required', 'max:50'],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return $this->update($args);
    }
}
