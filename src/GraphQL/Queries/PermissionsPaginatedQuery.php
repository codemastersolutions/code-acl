<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultPermission;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsPaginatedQuery;
use Rebing\GraphQL\Support\Query;

class PermissionsPaginatedQuery extends Query
{
    use DefaultPermission, ItemsPaginatedQuery;

    protected $attributes = [
        'name' => 'PermissionsPaginatedQuery',
        'description' => 'Retorna um coleção de permissões com paginação'
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['search_term']['description'] = $args['search_term']['description'] . ' de Permissões.';
        $args['search_field']['description'] = $args['search_field']['description'] . ' de Permissões.';
        return $args;
    }

    public function rules(array $args = []): array
    {
        return $this->baseRules();
    }
}
