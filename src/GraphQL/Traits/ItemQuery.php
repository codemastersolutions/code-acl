<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Ramsey\Uuid\Uuid;

trait ItemQuery
{
    use BaseQuery;

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::string(),
                'description' => 'Identificador único no formato UUID a ser consultado'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Nome a ser consultado'
            ],
            'slug' => [
                'type' => Type::string(),
                'description' => 'Identificador único no formato slug'
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $data = [];

        try {
            if (isset($args['id']) && Uuid::isValid($args['id'])) {
                $data = $this->model::findById($args['id']);
            }

            if (isset($args['slug'])) {
                $data = $this->model::findBySlug($args['slug']);
            }

            if (isset($args['name'])) {
                $data = $this->model::findByName($args['name']);
            }

            return $data;
        } catch (\Exception $e) {
            return $data;
        }
    }
}
