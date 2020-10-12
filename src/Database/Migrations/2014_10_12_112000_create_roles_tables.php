<?php

use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTables extends Migration
{
    private static $model;
    private static $modelUser;
    private static $modelPermission;
    private static $modelPermissionRole;
    private static $modelRoleUser;
    private static $conn;

    public function __construct() {
        self::$model = config('code-acl.models.role');
        self::$modelUser = app(config('code-acl.defaults.user'));
        self::$modelPermission = config('code-acl.models.permission');
        self::$modelPermissionRole = config('code-acl.models.role_has_permission');
        self::$modelRoleUser = config('code-acl.models.user_has_role');
        self::$conn = config('code-acl.defaults.connection');

        if (empty(self::$model) || empty(self::$conn)) {
            return new ConfigNotLoaded('config/code-acl.php');
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(self::$conn)->create(self::$model['table'], function (Blueprint $table) {
            switch (self::$model['primary_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$model['primary_key']['name'])->unique()->primary();
                    break;
                }
                case 'number': {
                    $table->bigIncrements(self::$model['primary_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$model['primary_key']['name'])->unique()->primary();
            }

            $table->string('name', 50)->unique();
            $table->string('slug');
            $table->timestamps();

            $table->index(['name'], 'lacl_roles_name_index');
        });

        Schema::connection(self::$conn)->create(self::$modelPermissionRole['table'], function (Blueprint $table) {
            switch (self::$modelPermissionRole['permission_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$modelPermissionRole['permission_key']['name']);
                    break;
                }
                case 'number': {
                    $table->unsignedBigInteger(self::$modelPermissionRole['permission_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$modelPermissionRole['permission_key']['name']);
            }

            switch (self::$modelPermissionRole['role_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$modelPermissionRole['role_key']['name']);
                    break;
                }
                case 'number': {
                    $table->unsignedBigInteger(self::$modelPermissionRole['role_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$modelPermissionRole['role_key']['name']);
            }

            $table->index(
                [
                    self::$modelPermissionRole['permission_key']['name'],
                    self::$modelPermissionRole['role_key']['name']
                ],
                'lacl_permission_role_index'
            );

            $table->foreign(self::$modelPermissionRole['role_key']['name'])
                ->references(self::$model['primary_key']['name'])
                ->on(self::$model['table'])
                ->onDelete('cascade');

            $table->foreign(self::$modelPermissionRole['permission_key']['name'])
                ->references(self::$modelPermission['primary_key']['name'])
                ->on(self::$modelPermission['table'])
                ->onDelete('cascade');

            $table->primary(
                [
                    self::$modelPermissionRole['permission_key']['name'],
                    self::$modelPermissionRole['role_key']['name']
                ],
                'lacl_permission_role_primary'
            );
        });

        Schema::connection(self::$conn)->create(self::$modelRoleUser['table'], function (Blueprint $table) {
            switch (self::$modelRoleUser['role_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$modelRoleUser['role_key']['name']);
                    break;
                }
                case 'number': {
                    $table->unsignedBigInteger(self::$modelRoleUser['role_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$modelRoleUser['role_key']['name']);
            }

            switch (self::$modelRoleUser['user_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$modelRoleUser['user_key']['name']);
                    break;
                }
                case 'number': {
                    $table->unsignedBigInteger(self::$modelRoleUser['user_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$modelRoleUser['user_key']['name']);
            }

            $table->index(
                [
                    self::$modelRoleUser['role_key']['name'],
                    self::$modelRoleUser['user_key']['name']
                ],
                'lacl_role_user_index'
            );

            $table->foreign(self::$modelRoleUser['role_key']['name'])
                ->references(self::$model['primary_key']['name'])
                ->on(self::$model['table'])
                ->onDelete('cascade');

            $table->foreign(self::$modelRoleUser['user_key']['name'])
                ->references(self::$modelUser->getKeyName())
                ->on(self::$modelUser->getTable())
                ->onDelete('cascade');

            $table->primary(
                [
                    self::$modelRoleUser['role_key']['name'],
                    self::$modelRoleUser['user_key']['name']
                ],
                'lacl_role_user_primary'
            );
        });

        app('cache')
            ->store(config('code-acl.cache.store') != 'default' ? config('code-acl.cache.store') : null)
            ->forget(config('code-acl.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(self::$conn)->table(self::$modelRoleUser['table'], function (Blueprint $table) {
            $table->dropIndex('lacl_role_user_index');
            $table->dropPrimary('lacl_role_user_primary');
        });
        Schema::connection(self::$conn)->table(self::$modelPermissionRole['table'], function (Blueprint $table) {
            $table->dropIndex('lacl_permission_role_index');
            $table->dropPrimary('lacl_permission_role_primary');
        });
        Schema::connection(self::$conn)->table(self::$model['table'], function (Blueprint $table) {
            $table->dropIndex('lacl_roles_name_index');
            $table->dropPrimary(self::$model['primary_key']['name']);
        });
        Schema::connection(self::$conn)->dropIfExists(self::$modelRoleUser['table']);
        Schema::connection(self::$conn)->dropIfExists(self::$modelPermissionRole['table']);
        Schema::connection(self::$conn)->dropIfExists(self::$model['table']);
    }
}
