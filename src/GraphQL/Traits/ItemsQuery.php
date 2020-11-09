<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;

trait ItemsQuery
{
    use BaseQuery;

    public function type(): Type
    {
        return Type::listOf(GraphQL::type(class_basename(\get_class($this->model))));
    }
}
