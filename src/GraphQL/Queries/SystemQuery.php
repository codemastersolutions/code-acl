<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use Closure;
use CodeMaster\CodeAcl\Contracts\System;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Ramsey\Uuid\Uuid;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class SystemQuery extends Query
{
    protected $attributes = [
        'name' => 'SystemQuery',
        'description' => 'Retorna um sistema'
    ];

    public function type(): Type
    {
        return GraphQL::type('System');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::string(),
                'description' => 'Identificador único do sistema no formato UUID a ser consultado'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Nome do sistema a ser consultado'
            ],
            'slug' => [
                'type' => Type::string(),
                'description' => 'Identificador único do sistema no formato slug'
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        try {
            $model = app(System::class);

            if (isset($args['id'])) {
                if (Uuid::isValid($args['id'])) {
                    return $model::findById($args['id']);
                }

                return [];
            }

            if (isset($args['slug'])) {
                return $model::findBySlug($args['slug']);
            }

            if (isset($args['name'])) {
                return $model::findByName($args['name']);
            }

            return $model::all();
        } catch (\Exception $e) {
            return [];
        }
    }
}
