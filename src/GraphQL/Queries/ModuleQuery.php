<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use Closure;
use CodeMaster\CodeAcl\Contracts\Module;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Ramsey\Uuid\Uuid;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ModuleQuery extends Query
{
    protected $attributes = [
        'name' => 'ModuleQuery',
        'description' => 'Retorna um módulo'
    ];

    public function type(): Type
    {
        return GraphQL::type('Module');
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::string(),
                'description' => 'Identificador único do módulo no formato UUID a ser consultado'
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Nome do módulo a ser consultado'
            ],
            'slug' => [
                'type' => Type::string(),
                'description' => 'Identificador único do módulo no formato slug'
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        try {
            $model = app(Module::class);

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
