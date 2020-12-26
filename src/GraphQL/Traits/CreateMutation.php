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

    public function baseArgs(): array
    {
        return [
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Nome'
            ]
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
        ];
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'name.required' => 'Please enter your full :attribute',
            'name.string' => 'Your :attribute must be a valid string',
            'name.max' => 'The :attribute may not be greater than :max characters.',
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return $this->store($args);
    }
}
