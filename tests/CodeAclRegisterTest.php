<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Permission;
use CodeMaster\CodeAcl\Contracts\Role;
use CodeMaster\CodeAcl\CodeAclRegister;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Collection;

class CodeAclRegisterTest extends TestCase
{
    /** @test */
    public function it_is_can_set_class_permissions()
    {
        $register = app(CodeAclRegister::class);

        $class = $register
                    ->setPermissionClass(config('code-acl.models.user_has_permission.class'))
                    ->setPermissionClass(Permission::class)
                    ->getPermissionClass();

        $this->assertInstanceOf(Permission::class, $class);
    }

    /** @test */
    public function it_is_can_set_class_roles()
    {
        $register = app(CodeAclRegister::class);

        $class = $register
                    ->setRoleClass(config('code-acl.models.user_has_role.class'))
                    ->setRoleClass(Role::class)
                    ->getRoleClass();

        $this->assertInstanceOf(Role::class, $class);
    }

    /** @test */
    public function it_is_can_forget_cached_permissions()
    {
        $register = app(CodeAclRegister::class);

        $this->assertIsBool($register->forgetCachedPermissions());
    }

    /** @test */
    public function it_is_can_register_permissions()
    {
        $register = app(CodeAclRegister::class);
        $this->assertIsBool($register->registerPermissions());
    }

    /** @test */
    public function it_is_can_get_cache_store_from_config()
    {
        app('config')->set('cache.store.default', null);
        app('config')->set('cache.stores', []);

        $this->assertNull(config('code-acl.cache.store.default'));
        $this->assertNull(config('code-acl.cache.stores'));

        $register = app(Register::class);

        $this->assertInstanceOf(Repository::class, $register->getCacheFromConfig());
    }

    /** @test */
    public function it_is_can_get_permissions()
    {
        $register = app(CodeAclRegister::class);
        $permission = $register->getPermissions(['name' => $this->testInsertPermission->name]);

        $this->assertCount(1, $permission);
        $this->assertInstanceOf(Collection::class, $permission);
        $this->assertInstanceOf(Permission::class, $permission->first());
        $this->assertEquals($this->testInsertPermission->name, $permission->first()->name);

        $permissions = $register->getPermissions([
            'name' => $this->testInsertPermission->name,
            'slug' => $this->testEditNews->slug
        ]);

        $this->assertCount(2, $permissions);
        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertInstanceOf(Permission::class, $permissions->first());
        $this->assertEquals($this->testInsertPermission->name, $permissions->where('id', $this->testInsertPermission->id)->first()->name);
        $this->assertEquals($this->testEditNews->slug, $permissions->where('id', $this->testEditNews->id)->first()->slug);
    }
}
