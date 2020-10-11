<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\CodeAclRegister;
use CodeMaster\CodeAcl\CodeAclServiceProvider;
use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /** @var \CodeMaster\CodeAcl\Test\User */
    protected $testUser, $testUser1;

    /** @var \CodeMaster\CodeAcl\Contracts\Permission */
    protected $testInsertPermission, $testEditPermission, $testUpdatePermission, $testDeletePermission, $testEditNews, $testInsertNews;

    /** @var \CodeMaster\CodeAcl\Contracts\Role */
    protected $testCreatorRole, $testPublisherRole, $testSupervisorRole, $testManangerRole;

    public function setUp(): void
    {
        parent::setUp();

        // Note: this also flushes the cache from within the migration
        $this->setUpDatabase($this->app);

        $this->testUser = User::whereEmail('test@user.com')->first();
        $this->testUser1 = User::whereEmail('test1@user.com')->first();
        $this->testInsertPermission = app(PermissionContract::class)->findByName('Insert Articles');
        $this->testEditPermission = app(PermissionContract::class)->findByName('Edit Articles');
        $this->testDeletePermission = app(PermissionContract::class)->findByName('Delete Articles');
        $this->testUpdatePermission = app(PermissionContract::class)->findByName('Update Articles');
        $this->testEditNews = app(PermissionContract::class)->findByName('Edit News');
        $this->testInsertNews = app(PermissionContract::class)->findByName('Insert News');
        $this->testDeleteNews = app(PermissionContract::class)->findByName('Delete News');
        $this->testUpdateNews = app(PermissionContract::class)->findByName('Update News');
        $this->testCreatorRole = app(RoleContract::class)->findByName('Creator');
        $this->testPublisherRole = app(RoleContract::class)->findByName('Publisher');
        $this->testSupervisorRole = app(RoleContract::class)->findByName('Supervisor');
        $this->testManangerRole = app(RoleContract::class)->findByName('Mananger');
        $this->testManangerRole->givePermissions($this->testEditNews);
        $this->testUser->givePermissions($this->testInsertPermission->name);
        $this->testUser->givePermissions($this->testEditPermission->name);
        $this->testUser->givePermissions($this->testDeletePermission->name);
        $this->testUser->givePermissions($this->testUpdatePermission->name);
        $this->testUser->giveRoles($this->testManangerRole);
        $this->testUser1->givePermissions('insert', 'delete');
        $this->testSupervisorRole->givePermissions($this->testEditNews->slug, $this->testEditPermission->slug);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            CodeAclServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('code-acl.defaults.connection', 'sqlite');
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Use test User model for users provider
        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('cache.prefix', 'cms_tests---');
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('email');
            $table->softDeletes();
        });

        $app['db']->connection()->getSchemaBuilder()->create('permission_soft_deleting_user', function (Blueprint $table) {
            $table->uuid('permission_id');
            $table->uuid('soft_deleting_user_id');
        });

        if (Cache::getStore() instanceof \Illuminate\Cache\DatabaseStore ||
            $app[CodeAclRegister::class]->getCacheStore() instanceof \Illuminate\Cache\DatabaseStore) {
            $this->createCacheTable();
        }

        include_once __DIR__.'/../src/Database/Migrations/2014_10_12_111000_create_permissions_tables.php';
        include_once __DIR__.'/../src/Database/Migrations/2014_10_12_112000_create_roles_tables.php';

        (new \CreatePermissionsTables())->up();
        (new \CreateRolesTables())->up();

        User::create(['email' => 'test@user.com']);
        User::create(['email' => 'test1@user.com']);
        $app[PermissionContract::class]->create(['name' => 'Insert Articles']);
        $app[PermissionContract::class]->create(['name' => 'Edit Articles']);
        $app[PermissionContract::class]->create(['name' => 'Delete Articles']);
        $app[PermissionContract::class]->create(['name' => 'Update Articles']);
        $app[PermissionContract::class]->create(['name' => 'Insert News']);
        $app[PermissionContract::class]->create(['name' => 'Edit News']);
        $app[PermissionContract::class]->create(['name' => 'Delete News']);
        $app[PermissionContract::class]->create(['name' => 'Update News']);
        $app[PermissionContract::class]->create(['name' => 'Insert']);
        $app[PermissionContract::class]->create(['name' => 'Update']);
        $app[PermissionContract::class]->create(['name' => 'Delete']);
        $app[PermissionContract::class]->create(['name' => 'List']);
        $app[RoleContract::class]->create(['name' => 'Creator']);
        $app[RoleContract::class]->create(['name' => 'Supervisor']);
        $app[RoleContract::class]->create(['name' => 'Publisher']);
        $app[RoleContract::class]->create(['name' => 'Mananger']);
        $app[RoleContract::class]->create(['name' => 'Role 1']);
        $app[RoleContract::class]->create(['name' => 'Role 2']);
    }

    /**
     * Reload the permissions.
     */
    protected function reloadPermissions()
    {
        app(CodeAclRegister::class)->forgetCachedPermissions();
    }

     /**
     * Create the table cache.
     */
    public function createCacheTable()
    {
        Schema::create('cache', function ($table) {
            $table->string('key')->unique();
            $table->text('value');
            $table->integer('expiration');
        });
    }
}
