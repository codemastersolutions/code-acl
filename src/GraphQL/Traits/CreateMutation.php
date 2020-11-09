<?php

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

trait CreateMutation
{
    use BaseMutation;

    public function type(): Type
    {
        return GraphQL::type(class_basename(\get_class($this->model)));
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
            'name' => ['required', 'max:50'],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return $this->store($args);
    }
}
