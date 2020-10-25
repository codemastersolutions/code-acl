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
    private static $modelRoleName;
    private static $modelRoleIndex;
    private static $modelPermissionRoleName;
    private static $modelPermissionRoleIndex;
    private static $modelPermissionRolePrimary;
    private static $modelRoleUserName;
    private static $modelRoleUserIndex;
    private static $modelRoleUserPrimary;

    public function __construct() {
        self::$model = config('code-acl.models.role');
        self::$modelUser = app(config('code-acl.defaults.user'));
        self::$modelPermission = config('code-acl.models.permission');
        self::$modelPermissionRole = config('code-acl.models.role_has_permission');
        self::$modelRoleUser = config('code-acl.models.user_has_role');
        self::$conn = config('code-acl.defaults.connection');

        if (empty(self::$model) || empty(self::$conn)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$modelRoleName = self::$model['table'];
        self::$modelRoleIndex = 'lacl_'.self::$modelRoleName.'_name_index';
        self::$modelPermissionRoleName = self::$modelPermissionRole['table'];
        self::$modelPermissionRoleIndex = 'lacl_'.self::$modelPermissionRoleName.'_index';
        self::$modelPermissionRolePrimary= 'lacl_'.self::$modelPermissionRoleName.'_primary';
        self::$modelRoleUserName = self::$modelRoleUser['table'];
        self::$modelRoleUserIndex = 'lacl_'.self::$modelRoleUserName.'_index';
        self::$modelRoleUserPrimary= 'lacl_'.self::$modelRoleUserName.'_primary';
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(self::$conn)->create(self::$modelRoleName, function (Blueprint $table) {
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

            $table->index(['name'], self::$modelRoleIndex);
        });

        Schema::connection(self::$conn)->create(self::$modelPermissionRoleName, function (Blueprint $table) {
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
                self::$modelPermissionRoleIndex
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
                self::$modelPermissionRolePrimary
            );
        });

        Schema::connection(self::$conn)->create(self::$modelRoleUserName, function (Blueprint $table) {
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
                self::$modelRoleUserIndex
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
                self::$modelRoleUserPrimary
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
        Schema::connection(self::$conn)->table(self::$modelRoleUserName, function (Blueprint $table) {
            $table->dropIndex(self::$modelRoleUserIndex);
            $table->dropPrimary(self::$modelRoleUserPrimary);
        });
        Schema::connection(self::$conn)->table(self::$modelPermissionRoleName, function (Blueprint $table) {
            $table->dropIndex(self::$modelPermissionRoleIndex);
            $table->dropPrimary(self::$modelPermissionRoleIndex);
        });
        Schema::connection(self::$conn)->table(self::$modelRoleName, function (Blueprint $table) {
            $table->dropIndex(self::$modelRoleIndex);
            $table->dropPrimary(self::$model['primary_key']['name']);
        });
        Schema::connection(self::$conn)->dropIfExists(self::$modelRoleUserName);
        Schema::connection(self::$conn)->dropIfExists(self::$modelPermissionRoleName);
        Schema::connection(self::$conn)->dropIfExists(self::$modelRoleName);
    }
}
