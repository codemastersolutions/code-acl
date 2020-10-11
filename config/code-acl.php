<?php

return [
    'defaults' => [
        'connection' => env('DB_CONNECTION'),
        'user' => config('auth.providers.users.model'),
        'code-acl' => [
            'domain' => env('CODEACL_DOMAIN', null),
            'path' => env('CODEACL_PATH', 'code-acl'),
            'middleware' => 'api'
        ]
    ],

    'models' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `CodeMaster\CodeAcl\Contracts\Permission` contract.
         */
        'permission' => [
            'class' => CodeMaster\CodeAcl\Models\Permission::class,
            'primary_key' => [
                /*
                 * Name of field for primary key model.
                 */
                'name' => 'id',
                /*
                 * Type of field for primary key model. Accepted `uuid` or `number`. Default is `uuid`.
                 *
                 * If you choose to use the primary key of type `number`, the field in the database will be of type unsigned bigint or equivalent.
                 */
                'type' => 'uuid',
                /*
                 * If you choose to use the primary key of type `number`, with auto increment, change the property below to true.
                 */
                'incrementing' => false
            ],
            'meta_data' => [
                'order_by' => [
                    'field' => 'created_at',
                    'direction' => 'desc'
                ],
                'pagination' => [
                    'per_page' => 15
                ]
            ],
            /*
             * Table name for Permission model.
             * If property is null,
             */
            'table' => 'permissions'
        ],

        /*
         *
         */
        'user_has_permission' => [
            'class' => CodeMaster\CodeAcl\Models\PermissionUserPivot::class,
            'user_key' => [
                /*
                * Name of field for morph key model.
                */
                'name' => 'user_id',
                /*
                * Type of field for morph key model. Accepted `uuid` or `number`. Default is `uuid`.
                *
                * If you choose to use the primary key of type `number`, the field in the database will be of type unsigned bigint or equivalent.
                */
                'type' => 'uuid',
            ],
            'permission_key' => [
                /*
                 * Name of field for primary key model.
                 */
                'name' => 'permission_id',
                /*
                 * Type of field for primary key model. Accepted `uuid` or `number`. Default is `uuid`.
                 *
                 * If you choose to use the primary key of type `number`, the field in the database will be of type unsigned bigint or equivalent.
                 */
                'type' => 'uuid',
            ],
            'meta_data' => [
                'order_by' => [
                    'field' => 'name',
                    'direction' => 'desc'
                ],
                'pagination' => [
                    'per_page' => 15
                ]
            ],
            /*
            * Table name for Permission model.
            */
            'table' => 'permission_user'
        ],

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `CodeMaster\CodeAcl\Contracts\Role` contract.
         */
        'role' => [
            'class' => CodeMaster\CodeAcl\Models\Role::class,
            'primary_key' => [
                /*
                 * Name of field for primary key model.
                 */
                'name' => 'id',
                /*
                 * Type of field for primary key model. Accepted `uuid` or `number`. Default is `uuid`.
                 *
                 * If you choose to use the primary key of type `number`, the field in the database will be of type unsigned bigint or equivalent.
                 */
                'type' => 'uuid',
                /*
                 * If you choose to use the primary key of type `number`, with auto increment, change the property below to true.
                 */
                'incrementing' => false
            ],
            'meta_data' => [
                'order_by' => [
                    'field' => 'created_at',
                    'direction' => 'desc'
                ],
                'pagination' => [
                    'per_page' => 15
                ]
            ],
            /*
             * Table name for Role model.
             * If property is null,
             */
            'table' => 'roles'
        ],

        /*
         *
         */
        'role_has_permission' => [
            'class' => CodeMaster\CodeAcl\Models\PermissionRolePivot::class,
            'role_key' => [
                /*
                * Name of field for morph key model.
                */
                'name' => 'role_id',
                /*
                * Type of field for morph key model. Accepted `uuid` or `number`. Default is `uuid`.
                *
                * If you choose to use the primary key of type `number`, the field in the database will be of type unsigned bigint or equivalent.
                */
                'type' => 'uuid',
            ],
            'permission_key' => [
                /*
                 * Name of field for primary key model.
                 */
                'name' => 'permission_id',
                /*
                 * Type of field for primary key model. Accepted `uuid` or `number`. Default is `uuid`.
                 *
                 * If you choose to use the primary key of type `number`, the field in the database will be of type unsigned bigint or equivalent.
                 */
                'type' => 'uuid',
            ],
            'meta_data' => [
                'order_by' => [
                    'field' => 'name',
                    'direction' => 'desc'
                ],
                'pagination' => [
                    'per_page' => 15
                ]
            ],
            /*
            * Table name for Permission model.
            */
            'table' => 'permission_role'
        ],

        /*
         *
         */
        'user_has_role' => [
            'class' => CodeMaster\CodeAcl\Models\RoleUserPivot::class,
            'role_key' => [
                /*
                * Name of field for morph key model.
                */
                'name' => 'role_id',
                /*
                * Type of field for morph key model. Accepted `uuid` or `number`. Default is `uuid`.
                *
                * If you choose to use the primary key of type `number`, the field in the database will be of type unsigned bigint or equivalent.
                */
                'type' => 'uuid',
            ],
            'user_key' => [
                /*
                 * Name of field for primary key model.
                 */
                'name' => 'user_id',
                /*
                 * Type of field for primary key model. Accepted `uuid` or `number`. Default is `uuid`.
                 *
                 * If you choose to use the primary key of type `number`, the field in the database will be of type unsigned bigint or equivalent.
                 */
                'type' => 'uuid',
            ],
            'meta_data' => [
                'order_by' => [
                    'field' => 'name',
                    'direction' => 'desc'
                ],
                'pagination' => [
                    'per_page' => 15
                ]
            ],
            /*
            * Table name for Permission model.
            */
            'table' => 'role_user'
        ],
    ],

    /*
     * When set to true, the required permission names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */
    'display_permission_in_exception' => false,

    /*
     * When set to true, the required system names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */
    'display_system_in_exception' => false,

    /*
     * When set to true, the required module names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */
    'display_module_in_exception' => false,

    /*
     * When set to true, the required role names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    'display_role_in_exception' => false,

    'cache' => [

        /*
         * By default all permissions are cached for 24 hours to speed up performance.
         * When permissions or roles are updated the cache is flushed automatically.
         */

        'expiration_time' => \DateInterval::createFromDateString('24 hours'),

        /*
         * The cache key used to store all permissions.
         */

        'key' => 'codemaster.code_acl.cache',

        /*
         * When checking for a permission against a model by passing a Permission
         * instance to the check, this key determines what attribute on the
         * Permissions model is used to cache against.
         *
         * Ideally, this should match your preferred way of checking permissions, eg:
         * `$user->can('view-posts')` would be 'name'.
         */

        'model_key' => 'slug',

        /*
         * You may optionally indicate a specific cache driver to use for permission and
         * role caching using any of the `store` drivers listed in the cache.php config
         * file. Using 'default' here means to use the `default` set in cache.php.
         */

        'store' => 'default',
    ],

    'logging' => [
        'channel' => env('CODEACL_LOG_CHANNEL', 'file'),

        'channels' => [
            'file' => [
                'path' => storage_path('logs/code-acl.log')
            ]
        ]
    ],
];
