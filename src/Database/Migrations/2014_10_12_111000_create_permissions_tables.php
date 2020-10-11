<?php

use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTables extends Migration
{
    private static $model;
    private static $modelUser;
    private static $conn;
    private static $modelPermissionUser;

    public function __construct() {
        self::$model = config('code-acl.models.permission');
        self::$modelUser = app(config('code-acl.defaults.user'));
        self::$modelPermissionUser = config('code-acl.models.user_has_permission');
        self::$conn = config('code-acl.defaults.connection');

        if (empty(self::$model) || empty(self::$conn)) {
            new ConfigNotLoaded();
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

            $table->index(['name'], 'lacl_permissions_name_index');
        });

        Schema::connection(self::$conn)->create(self::$modelPermissionUser['table'], function (Blueprint $table) {
            switch (self::$modelPermissionUser['permission_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$modelPermissionUser['permission_key']['name']);
                    break;
                }
                case 'number': {
                    $table->unsignedBigInteger(self::$modelPermissionUser['permission_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$modelPermissionUser['permission_key']['name']);
            }

            switch (self::$modelPermissionUser['user_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$modelPermissionUser['user_key']['name']);
                    break;
                }
                case 'number': {
                    $table->unsignedBigInteger(self::$modelPermissionUser['user_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$modelPermissionUser['user_key']['name']);
            }

            $table->index(
                [
                    self::$modelPermissionUser['permission_key']['name'],
                    self::$modelPermissionUser['user_key']['name']
                ],
                'lacl_permission_user_index'
            );

            $table->foreign(self::$modelPermissionUser['permission_key']['name'])
                ->references(self::$model['primary_key']['name'])
                ->on(self::$model['table'])
                ->onDelete('cascade');

            $table->foreign(self::$modelPermissionUser['user_key']['name'])
                ->references(self::$modelUser->getKeyName())
                ->on(self::$modelUser->getTable())
                ->onDelete('cascade');

            $table->primary(
                [
                    self::$modelPermissionUser['permission_key']['name'],
                    self::$modelPermissionUser['user_key']['name']
                ],
                'lacl_permission_user_primary'
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
        Schema::connection(self::$conn)->table(self::$modelPermissionUser['table'], function (Blueprint $table) {
            $table->dropIndex('lacl_permission_user_index');
            $table->dropPrimary('lacl_permission_user_primary');
        });
        Schema::connection(self::$conn)->table(self::$model['table'], function (Blueprint $table) {
            $table->dropIndex('lacl_permissions_name_index');
            $table->dropIndex('lacl_permissions_name_index');
            $table->dropPrimary(self::$model['primary_key']['name']);
        });
        Schema::connection(self::$conn)->dropIfExists(self::$modelPermissionUser['table']);
        Schema::connection(self::$conn)->dropIfExists(self::$model['table']);
    }
}
