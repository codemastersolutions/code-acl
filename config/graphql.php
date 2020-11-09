<?php

declare(strict_types=1);

return [
    'prefix' => 'graphql',

    'routes' => '{graphql_schema?}',

    'controllers' => \Rebing\GraphQL\GraphQLController::class.'@query',

    'middleware' => [],

    'route_group_attributes' => [],

    'default_schema' => 'default',

    'schemas' => [
        'default' => [
            'query' => [],
            'mutation' => [],
            'middleware' => [],
            'method' => ['get', 'post'],
        ],

        'code_acl' => [
            'query' => [
                'module' => \CodeMaster\CodeAcl\GraphQL\Queries\ModuleQuery::class,
                'modules' => \CodeMaster\CodeAcl\GraphQL\Queries\ModulesQuery::class,
                'modules_paginated' => \CodeMaster\CodeAcl\GraphQL\Queries\ModulesPaginatedQuery::class,
                'permission' => \CodeMaster\CodeAcl\GraphQL\Queries\PermissionQuery::class,
                'permissions' => \CodeMaster\CodeAcl\GraphQL\Queries\PermissionsQuery::class,
                'permissions_paginated' => \CodeMaster\CodeAcl\GraphQL\Queries\PermissionsPaginatedQuery::class,
                'role' => \CodeMaster\CodeAcl\GraphQL\Queries\RoleQuery::class,
                'roles' => \CodeMaster\CodeAcl\GraphQL\Queries\RolesQuery::class,
                'roles_paginated' => \CodeMaster\CodeAcl\GraphQL\Queries\RolesPaginatedQuery::class,
                'system' => \CodeMaster\CodeAcl\GraphQL\Queries\SystemQuery::class,
                'systems' => \CodeMaster\CodeAcl\GraphQL\Queries\SystemsQuery::class,
                'systems_paginated' => \CodeMaster\CodeAcl\GraphQL\Queries\SystemsPaginatedQuery::class,
            ],
            'mutation' => [
                'create_module' => \CodeMaster\CodeAcl\GraphQL\Mutations\ModuleCreateMutation::class,
                'delete_module' => \CodeMaster\CodeAcl\GraphQL\Mutations\ModuleDeleteMutation::class,
                'update_module' => \CodeMaster\CodeAcl\GraphQL\Mutations\ModuleUpdateMutation::class,
                'create_permission' => \CodeMaster\CodeAcl\GraphQL\Mutations\PermissionCreateMutation::class,
                'delete_permission' => \CodeMaster\CodeAcl\GraphQL\Mutations\PermissionDeleteMutation::class,
                'update_permission' => \CodeMaster\CodeAcl\GraphQL\Mutations\PermissionUpdateMutation::class,
                'create_role' => \CodeMaster\CodeAcl\GraphQL\Mutations\RoleCreateMutation::class,
                'delete_role' => \CodeMaster\CodeAcl\GraphQL\Mutations\RoleDeleteMutation::class,
                'update_role' => \CodeMaster\CodeAcl\GraphQL\Mutations\RoleUpdateMutation::class,
                'create_system' => \CodeMaster\CodeAcl\GraphQL\Mutations\SystemCreateMutation::class,
                'delete_system' => \CodeMaster\CodeAcl\GraphQL\Mutations\SystemDeleteMutation::class,
                'update_system' => \CodeMaster\CodeAcl\GraphQL\Mutations\SystemUpdateMutation::class,
            ],
            'middleware' => [],
            'method' => ['get', 'post'],
        ],
    ],

    'types' => [
        'Module' => \CodeMaster\CodeAcl\GraphQL\Types\ModuleType::class,
        'Permission' => \CodeMaster\CodeAcl\GraphQL\Types\PermissionType::class,
        'Role' => \CodeMaster\CodeAcl\GraphQL\Types\RoleType::class,
        'System' => \CodeMaster\CodeAcl\GraphQL\Types\SystemType::class,
        'User' => \CodeMaster\CodeAcl\GraphQL\Types\UserType::class,
    ],

    'lazyload_types' => false,

    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

    'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],

    'params_key' => 'variables',

    'security' => [
        'query_max_complexity' => null,
        'query_max_depth' => null,
        'disable_introspection' => false,
    ],

    'pagination_type' => \Rebing\GraphQL\Support\PaginationType::class,

    'graphiql' => [
        'prefix' => '/graphiql',
        'controller' => \Rebing\GraphQL\GraphQLController::class.'@graphiql',
        'middleware' => [],
        'view' => 'graphql::graphiql',
        'display' => env('ENABLE_GRAPHIQL', true),
    ],

    'defaultFieldResolver' => null,

    'headers' => [],

    'json_encoding_options' => 0,
];
