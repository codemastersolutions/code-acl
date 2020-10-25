<?php

use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTables extends Migration
{
    private static $model;
    private static $modelUser;
    private static $modelModuleUser;
    private static $conn;
    private static $modelModuleName;
    private static $modelModuleIndex;
    private static $modelModuleUserName;
    private static $modelModuleUserIndex;
    private static $modelModuleUserPrimary;

    public function __construct() {
        self::$model = config('code-acl.models.module');
        self::$modelUser = app(config('code-acl.defaults.user'));
        self::$modelModuleUser = config('code-acl.models.user_has_module');
        self::$conn = config('code-acl.defaults.connection');

        if (empty(self::$model) || empty(self::$conn)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$modelModuleName = self::$model['table'];
        self::$modelModuleIndex = 'lacl_'.self::$modelModuleName.'_name_index';
        self::$modelModuleUserName = self::$modelModuleUser['table'];
        self::$modelModuleUserIndex = 'lacl_'.self::$modelModuleUserName.'_index';
        self::$modelModuleUserPrimary= 'lacl_'.self::$modelModuleUserName.'_primary';
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(self::$conn)->create(self::$modelModuleName, function (Blueprint $table) {
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

            $table->index(['name'], self::$modelModuleIndex);
        });

        Schema::connection(self::$conn)->create(self::$modelModuleUserName, function (Blueprint $table) {
            switch (self::$modelModuleUser['module_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$modelModuleUser['module_key']['name']);
                    break;
                }
                case 'number': {
                    $table->unsignedBigInteger(self::$modelModuleUser['module_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$modelModuleUser['module_key']['name']);
            }

            switch (self::$modelModuleUser['user_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$modelModuleUser['user_key']['name']);
                    break;
                }
                case 'number': {
                    $table->unsignedBigInteger(self::$modelModuleUser['user_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$modelModuleUser['user_key']['name']);
            }

            $table->index(
                [
                    self::$modelModuleUser['module_key']['name'],
                    self::$modelModuleUser['user_key']['name']
                ],
                self::$modelModuleUserIndex
            );

            $table->foreign(self::$modelModuleUser['module_key']['name'])
                ->references(self::$model['primary_key']['name'])
                ->on(self::$model['table'])
                ->onDelete('cascade');

            $table->foreign(self::$modelModuleUser['user_key']['name'])
                ->references(self::$modelUser->getKeyName())
                ->on(self::$modelUser->getTable())
                ->onDelete('cascade');

            $table->primary(
                [
                    self::$modelModuleUser['module_key']['name'],
                    self::$modelModuleUser['user_key']['name']
                ],
                self::$modelModuleUserPrimary
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
        Schema::connection(self::$conn)->table(self::$modelModuleUserName, function (Blueprint $table) {
            $table->dropIndex(self::$modelModuleUserIndex);
            $table->dropPrimary(self::$modelModuleUserPrimary);
        });
        Schema::connection(self::$conn)->table(self::$modelModuleName, function (Blueprint $table) {
            $table->dropIndex(self::$modelModuleIndex);
            $table->dropPrimary(self::$model['primary_key']['name']);
        });
        Schema::connection(self::$conn)->dropIfExists(self::$modelModuleUserName);
        Schema::connection(self::$conn)->dropIfExists(self::$modelModuleName);
    }
}
