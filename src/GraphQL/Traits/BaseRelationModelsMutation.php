<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use GraphQL\Type\Definition\Type;

trait BaseRelationModelsMutation
{
    protected $model;

    public function type(): Type
    {
        return Type::boolean();
    }

    public function baseArgs(): array
    {
        return [
            'modelIdOrSlug' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'relationIdOrSlug' => [
                'type' => Type::nonNull(Type::string()),
            ],
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'modelIdOrSlug' => ['required'],
            'relationIdOrSlug' => ['required'],
        ];
    }
}
