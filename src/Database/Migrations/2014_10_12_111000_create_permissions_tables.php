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
    private static $modelPermissionName;
    private static $modelPermissionIndex;
    private static $modelPermissionUserName;
    private static $modelPermissionUserIndex;
    private static $modelPermissionUserPrimary;

    public function __construct() {
        self::$model = config('code-acl.models.permission');
        self::$modelUser = app(config('code-acl.defaults.user'));
        self::$modelPermissionUser = config('code-acl.models.user_has_permission');
        self::$conn = config('code-acl.defaults.connection');

        if (empty(self::$model) || empty(self::$conn)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$modelPermissionName = self::$model['table'];
        self::$modelPermissionUserName = self::$modelPermissionUser['table'];
        self::$modelPermissionIndex = 'lacl_'.self::$modelPermissionName.'_name_index';
        self::$modelPermissionUserIndex = 'lacl_'.self::$modelPermissionUserName.'_index';
        self::$modelPermissionUserPrimary= 'lacl_'.self::$modelPermissionUserName.'_primary';
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(self::$conn)->create(self::$modelPermissionName, function (Blueprint $table) {
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

            $table->index(['name'], self::$modelPermissionIndex);
        });


        Schema::connection(self::$conn)->create(self::$modelPermissionUserName, function (Blueprint $table) {
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
                self::$modelPermissionUserIndex
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
                self::$modelPermissionUserPrimary
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
        Schema::connection(self::$conn)->table(self::$modelPermissionUserName, function (Blueprint $table) {
            $table->dropIndex(self::$modelPermissionUserIndex);
            $table->dropPrimary(self::$modelPermissionUserPrimary);
        });
        Schema::connection(self::$conn)->table(self::$modelPermissionName, function (Blueprint $table) {
            $table->dropIndex(self::$modelPermissionIndex);
            $table->dropPrimary(self::$model['primary_key']['name']);
        });
        Schema::connection(self::$conn)->dropIfExists(self::$modelPermissionUserName);
        Schema::connection(self::$conn)->dropIfExists(self::$modelPermissionName);
    }
}
