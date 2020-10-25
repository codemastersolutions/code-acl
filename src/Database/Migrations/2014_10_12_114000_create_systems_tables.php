<?php

use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemsTables extends Migration
{
    private static $model;
    private static $modelUser;
    private static $modelSystemUser;
    private static $conn;
    private static $modelSystemName;
    private static $modelSystemIndex;
    private static $modelSystemUserName;
    private static $modelSystemUserIndex;
    private static $modelSystemUserPrimary;

    public function __construct() {
        self::$model = config('code-acl.models.system');
        self::$modelUser = app(config('code-acl.defaults.user'));
        self::$modelSystemUser = config('code-acl.models.user_has_system');
        self::$conn = config('code-acl.defaults.connection');

        if (empty(self::$model) || empty(self::$conn)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$modelSystemName = self::$model['table'];
        self::$modelSystemIndex = 'lacl_'.self::$modelSystemName.'_name_index';
        self::$modelSystemUserName = self::$modelSystemUser['table'];
        self::$modelSystemUserIndex = 'lacl_'.self::$modelSystemUserName.'_index';
        self::$modelSystemUserPrimary= 'lacl_'.self::$modelSystemUserName.'_primary';
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(self::$conn)->create(self::$modelSystemName, function (Blueprint $table) {
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

            $table->index(['name'], self::$modelSystemIndex);
        });

        Schema::connection(self::$conn)->create(self::$modelSystemUserName, function (Blueprint $table) {
            switch (self::$modelSystemUser['system_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$modelSystemUser['system_key']['name']);
                    break;
                }
                case 'number': {
                    $table->unsignedBigInteger(self::$modelSystemUser['system_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$modelSystemUser['system_key']['name']);
            }

            switch (self::$modelSystemUser['user_key']['type']) {
                case 'uuid': {
                    $table->uuid(self::$modelSystemUser['user_key']['name']);
                    break;
                }
                case 'number': {
                    $table->unsignedBigInteger(self::$modelSystemUser['user_key']['name']);
                    break;
                }
                default:
                    $table->uuid(self::$modelSystemUser['user_key']['name']);
            }

            $table->index(
                [
                    self::$modelSystemUser['system_key']['name'],
                    self::$modelSystemUser['user_key']['name']
                ],
                self::$modelSystemUserIndex
            );

            $table->foreign(self::$modelSystemUser['system_key']['name'])
                ->references(self::$model['primary_key']['name'])
                ->on(self::$model['table'])
                ->onDelete('cascade');

            $table->foreign(self::$modelSystemUser['user_key']['name'])
                ->references(self::$modelUser->getKeyName())
                ->on(self::$modelUser->getTable())
                ->onDelete('cascade');

            $table->primary(
                [
                    self::$modelSystemUser['system_key']['name'],
                    self::$modelSystemUser['user_key']['name']
                ],
                self::$modelSystemUserPrimary
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
        Schema::connection(self::$conn)->table(self::$modelSystemUserName, function (Blueprint $table) {
            $table->dropIndex(self::$modelSystemUserIndex);
            $table->dropPrimary(self::$modelSystemUserPrimary);
        });
        Schema::connection(self::$conn)->table(self::$model['table'], function (Blueprint $table) {
            $table->dropIndex(self::$modelSystemIndex);
            $table->dropPrimary(self::$model['primary_key']['name']);
        });
        Schema::connection(self::$conn)->dropIfExists(self::$modelSystemUserName);
        Schema::connection(self::$conn)->dropIfExists(self::$modelSystemName);
    }
}
