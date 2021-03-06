<?php

return [
    'defaults' => [
        'connection' => env('DB_CONNECTION'),
        'user' => config('auth.providers.users.model'),
        'user_events' => [
            'created' => CodeMaster\CodeAcl\Events\User\UserCreated::class,
            'deleted' => CodeMaster\CodeAcl\Events\User\UserDeleted::class,
            // 'retrieved' => CodeMaster\CodeAcl\Events\User\UserRetrieved::class,
            // 'saved' => CodeMaster\CodeAcl\Events\User\UserSaved::class,
            'updated' => CodeMaster\CodeAcl\Events\User\UserUpdated::class,
        ],
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
            'events' => [
                'created' => CodeMaster\CodeAcl\Events\Permission\PermissionCreated::class,
                'deleted' => CodeMaster\CodeAcl\Events\Permission\PermissionDeleted::class,
                // 'retrieved' => CodeMaster\CodeAcl\Events\Permission\PermissionRetrieved::class,
                // 'saved' => CodeMaster\CodeAcl\Events\Permission\PermissionSaved::class,
                'updated' => CodeMaster\CodeAcl\Events\Permission\PermissionUpdated::class,
            ],
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
            'events' => [
                'created' => CodeMaster\CodeAcl\Events\Role\RoleCreated::class,
                'deleted' => CodeMaster\CodeAcl\Events\Role\RoleDeleted::class,
                // 'retrieved' => CodeMaster\CodeAcl\Events\Role\RoleRetrieved::class,
                // 'saved' => CodeMaster\CodeAcl\Events\Role\RoleSaved::class,
                'updated' => CodeMaster\CodeAcl\Events\Role\RoleUpdated::class,
            ],
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

        /*
         * When using the "HasModules" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your modules. Of course, it
         * is often just the "Module" model but you may use whatever you like.
         *
         * The model you want to use as a Module model needs to implement the
         * `CodeMaster\CodeAcl\Contracts\Module` contract.
         */
        'module' => [
            'class' => CodeMaster\CodeAcl\Models\Module::class,
            'events' => [
                'created' => CodeMaster\CodeAcl\Events\Module\ModuleCreated::class,
                'deleted' => CodeMaster\CodeAcl\Events\Module\ModuleDeleted::class,
                // 'retrieved' => CodeMaster\CodeAcl\Events\Module\ModuleRetrieved::class,
                // 'saved' => CodeMaster\CodeAcl\Events\Module\ModuleSaved::class,
                'updated' => CodeMaster\CodeAcl\Events\Module\ModuleUpdated::class,
            ],
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
             * Table name for Module model.
             * If property is null,
             */
            'table' => 'modules'
        ],

        /*
         *
         */
        'user_has_module' => [
            'class' => CodeMaster\CodeAcl\Models\ModuleUserPivot::class,
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
            'module_key' => [
                /*
                 * Name of field for primary key model.
                 */
                'name' => 'module_id',
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
            * Table name for Module model.
            */
            'table' => 'module_user'
        ],

        /*
         * When using the "HasSystems" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your systems. Of course, it
         * is often just the "System" model but you may use whatever you like.
         *
         * The model you want to use as a System model needs to implement the
         * `CodeMaster\CodeAcl\Contracts\System` contract.
         */
        'system' => [
            'class' => CodeMaster\CodeAcl\Models\System::class,
            'events' => [
                'created' => CodeMaster\CodeAcl\Events\System\SystemCreated::class,
                'deleted' => CodeMaster\CodeAcl\Events\System\SystemDeleted::class,
                // 'retrieved' => CodeMaster\CodeAcl\Events\System\SystemRetrieved::class,
                // 'saved' => CodeMaster\CodeAcl\Events\System\SystemSaved::class,
                'updated' => CodeMaster\CodeAcl\Events\System\SystemUpdated::class,
            ],
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
             * Table name for Module model.
             * If property is null,
             */
            'table' => 'systems'
        ],

        /*
         *
         */
        'user_has_system' => [
            'class' => CodeMaster\CodeAcl\Models\SystemUserPivot::class,
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
            'system_key' => [
                /*
                 * Name of field for primary key model.
                 */
                'name' => 'system_id',
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
            * Table name for Module model.
            */
            'table' => 'system_user'
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
    ]
];
