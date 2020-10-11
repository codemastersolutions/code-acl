<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\CodeAclRegister;
use CodeMaster\CodeAcl\Contracts\Permission;
use CodeMaster\CodeAcl\Contracts\Role;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CacheTest extends TestCase
{
    protected $cache_init_count = 0;
    protected $cache_load_count = 0;
    protected $cache_run_count = 2; // roles lookup, permissions lookup
    protected $cache_relations_count = 1;

    protected $register;

    public function setUp(): void
    {
        parent::setUp();

        $this->register = app(CodeAclRegister::class);

        $this->register->forgetCachedPermissions();

        DB::connection()->enableQueryLog();

        $cacheStore = $this->register->getCacheStore();

        switch (true) {
            case $cacheStore instanceof \Illuminate\Cache\DatabaseStore:
                $this->cache_init_count = 1;
                $this->cache_load_count = 1;
            default:
        }
    }

    /** @test */
    public function it_can_cache_the_permissions()
    {
        $this->resetQueryCount();

        $this->register->getPermissions();

        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count);
    }

    /** @test */
    public function it_flushes_the_cache_when_creating_a_permission()
    {
        app(Permission::class)->create(['name' => 'new']);

        $this->resetQueryCount();

        $this->register->getPermissions();

        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count);
    }

    /** @test */
    public function it_flushes_the_cache_when_updating_a_permission()
    {
        $permission = app(Permission::class)->create(['name' => 'new']);

        $permission->name = 'other name';
        $permission->save();

        $this->resetQueryCount();

        $this->register->getPermissions();

        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count);
    }

    /** @test */
    public function it_flushes_the_cache_when_creating_a_role()
    {
        app(Role::class)->create(['name' => 'new']);

        $this->resetQueryCount();

        $this->register->getPermissions();

        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count);
    }

    /** @test */
    public function it_flushes_the_cache_when_updating_a_role()
    {
        $role = app(Role::class)->create(['name' => 'new']);

        $role->name = 'other name';
        $role->save();

        $this->resetQueryCount();

        $this->register->getPermissions();

        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count);
    }

    /** @test */
    public function it_flushes_the_cache_when_removing_a_role_from_a_user()
    {
        $this->testUser->assignRole('testRole');

        $this->register->getPermissions();

        $this->testUser->removeRole('testRole');

        $this->resetQueryCount();

        $this->register->getPermissions();

        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count);
    }

    /** @test */
    public function user_creation_should_not_flush_the_cache()
    {
        $this->register->getPermissions();

        User::create(['email' => 'new']);

        $this->resetQueryCount();

        $this->register->getPermissions();

        // should all be in memory, so no init/load required
        $this->assertQueryCount(0);
    }

    /** @test */
    public function it_flushes_the_cache_when_giving_a_permission_to_a_role()
    {
        $this->testUserRole->givePermissionTo($this->testUserPermission);

        $this->resetQueryCount();

        $this->register->getPermissions();

        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count);
    }

    /** @test */
    public function has_permission_to_should_use_the_cache()
    {
        $this->testUserRole->givePermissionTo(['edit-articles', 'edit-news', 'Edit News']);
        $this->testUser->assignRole('testRole');

        $this->resetQueryCount();
        $this->assertTrue($this->testUser->hasPermissionTo('edit-articles'));
        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count + $this->cache_relations_count);

        $this->resetQueryCount();
        $this->assertTrue($this->testUser->hasPermissionTo('edit-news'));
        $this->assertQueryCount(0);

        $this->resetQueryCount();
        $this->assertTrue($this->testUser->hasPermissionTo('edit-articles'));
        $this->assertQueryCount(0);

        $this->resetQueryCount();
        $this->assertTrue($this->testUser->hasPermissionTo('Edit News'));
        $this->assertQueryCount(0);
    }

    /** @test */
    public function get_all_permissions_should_use_the_cache()
    {
        $this->testUserRole->givePermissionTo($expected = ['edit-articles', 'edit-news']);
        $this->testUser->assignRole('testRole');

        $this->resetQueryCount();
        $this->register->getPermissions();
        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count);

        $this->resetQueryCount();
        $actual = $this->testUser->getAllPermissions()->pluck('name')->sort()->values();
        $this->assertEquals($actual, collect($expected));

        $this->assertQueryCount(2);
    }

    /** @test */
    public function it_can_reset_the_cache_with_artisan_command()
    {
        Artisan::call('laravel-acl:create-permission', ['name' => 'new-permission']);
        $this->assertCount(1, \CodeMaster\CodeAcl\Models\Permission::where('name', 'new-permission')->get());

        $this->resetQueryCount();
        // retrieve permissions, and assert that the cache had to be loaded
        $this->register->getPermissions();
        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count);

        // reset the cache
        Artisan::call('laravel-acl:cache-reset');

        $this->resetQueryCount();
        $this->register->getPermissions();
        // assert that the cache had to be reloaded
        $this->assertQueryCount($this->cache_init_count + $this->cache_load_count + $this->cache_run_count);
    }

    protected function assertQueryCount(int $expected)
    {
        $this->assertCount($expected, DB::getQueryLog());
    }

    protected function resetQueryCount()
    {
        DB::flushQueryLog();
    }
}
