<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Queries;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUser;
use CodeMaster\CodeAcl\GraphQL\Traits\ItemsPaginatedQuery;
use Rebing\GraphQL\Support\Query;

class UsersPaginatedQuery extends Query
{
    use DefaultUser, ItemsPaginatedQuery;

    protected $attributes = [
        'name' => 'UsersPaginatedQuery',
        'description' => 'Retorna uma coleção de usuários paginada',
    ];

    public function args(): array
    {
        $args = $this->baseArgs();
        $args['search_term']['description'] = $args['search_term']['description'] . ' de Usuários.';
        $args['search_field']['description'] = $args['search_field']['description'] . ' de Usuários.';
        return $args;
    }

    public function rules(array $args = []): array
    {
        return $this->baseRules();
    }
}
