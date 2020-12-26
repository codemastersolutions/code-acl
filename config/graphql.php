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
                'users' => \CodeMaster\CodeAcl\GraphQL\Queries\UsersQuery::class,
                'users_paginated' => \CodeMaster\CodeAcl\GraphQL\Queries\UsersPaginatedQuery::class,
            ],
            'mutation' => [
                'create_module' => \CodeMaster\CodeAcl\GraphQL\Mutations\ModuleCreateMutation::class,
                'create_permission' => \CodeMaster\CodeAcl\GraphQL\Mutations\PermissionCreateMutation::class,
                'create_role' => \CodeMaster\CodeAcl\GraphQL\Mutations\RoleCreateMutation::class,
                'create_system' => \CodeMaster\CodeAcl\GraphQL\Mutations\SystemCreateMutation::class,
                'delete_module' => \CodeMaster\CodeAcl\GraphQL\Mutations\ModuleDeleteMutation::class,
                'delete_permission' => \CodeMaster\CodeAcl\GraphQL\Mutations\PermissionDeleteMutation::class,
                'delete_role' => \CodeMaster\CodeAcl\GraphQL\Mutations\RoleDeleteMutation::class,
                'delete_system' => \CodeMaster\CodeAcl\GraphQL\Mutations\SystemDeleteMutation::class,
                'delete_user' => \CodeMaster\CodeAcl\GraphQL\Mutations\UserDeleteMutation::class,
                'detach_module_to_user' => \CodeMaster\CodeAcl\GraphQL\Mutations\DetachModuleToUserMutation::class,
                'detach_permission_to_role' => \CodeMaster\CodeAcl\GraphQL\Mutations\DetachPermissionToRoleMutation::class,
                'detach_permission_to_user' => \CodeMaster\CodeAcl\GraphQL\Mutations\DetachPermissionToUserMutation::class,
                'detach_role_to_user' => \CodeMaster\CodeAcl\GraphQL\Mutations\DetachRoleToUserMutation::class,
                'detach_system_to_user' => \CodeMaster\CodeAcl\GraphQL\Mutations\DetachSystemToUserMutation::class,
                'give_module_to_user' => \CodeMaster\CodeAcl\GraphQL\Mutations\GiveModuleToUserMutation::class,
                'give_permission_to_role' => \CodeMaster\CodeAcl\GraphQL\Mutations\GivePermissionToRoleMutation::class,
                'give_permission_to_user' => \CodeMaster\CodeAcl\GraphQL\Mutations\GivePermissionToUserMutation::class,
                'give_role_to_user' => \CodeMaster\CodeAcl\GraphQL\Mutations\GiveRoleToUserMutation::class,
                'give_system_to_user' => \CodeMaster\CodeAcl\GraphQL\Mutations\GiveSystemToUserMutation::class,
                'update_module' => \CodeMaster\CodeAcl\GraphQL\Mutations\ModuleUpdateMutation::class,
                'update_permission' => \CodeMaster\CodeAcl\GraphQL\Mutations\PermissionUpdateMutation::class,
                'update_role' => \CodeMaster\CodeAcl\GraphQL\Mutations\RoleUpdateMutation::class,
                'update_system' => \CodeMaster\CodeAcl\GraphQL\Mutations\SystemUpdateMutation::class,
            ],
            'middleware' => [
                // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
                // 'auth:sanctum'
            ],
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
