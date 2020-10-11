<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Exceptions\PermissionAlreadyExists;
use CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\PermissionException;
use Ramsey\Uuid\Uuid;

class PermissionTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_the_permission_already_exists()
    {
        $this->expectException(PermissionAlreadyExists::class);

        app(PermissionContract::class)->create(['name' => 'test-permission']);
        app(PermissionContract::class)->create(['name' => 'test-permission']);
    }

    /** @test */
    public function it_throws_an_exception_when_the_permission_not_exists_in_find_by_id()
    {
        $this->expectException(PermissionDoesNotExist::class);

        app(PermissionContract::class)->findById(Uuid::uuid4());
    }

    /** @test */
    public function it_throws_an_exception_when_the_permission_not_exists_in_find_by_name()
    {
        $this->expectException(PermissionDoesNotExist::class);

        app(PermissionContract::class)->findByName('name not exists');
    }

    /** @test */
    public function it_throws_an_exception_when_the_permission_not_exists_in_find_by_slug()
    {
        $this->expectException(PermissionDoesNotExist::class);

        app(PermissionContract::class)->findBySlug('slug-not-exists');
    }

    /** @test */
    public function it_is_retrievable_by_id()
    {
        $permission = app(PermissionContract::class)->findById($this->testEditPermission->id);

        $this->assertEquals($this->testEditPermission->id, $permission->id);
        $this->assertEquals($this->testEditPermission->name, $permission->name);
        $this->assertEquals($this->testEditPermission->slug, $permission->slug);
    }

    /** @test */
    public function it_is_retrievable_by_name()
    {
        $permission = app(PermissionContract::class)->findByName($this->testEditPermission->name);

        $this->assertEquals($this->testEditPermission->id, $permission->id);
        $this->assertEquals($this->testEditPermission->name, $permission->name);
        $this->assertEquals($this->testEditPermission->slug, $permission->slug);
    }

    /** @test */
    public function it_is_retrievable_by_slug()
    {
        $permission = app(PermissionContract::class)->findBySlug($this->testEditPermission->slug);

        $this->assertEquals($this->testEditPermission->id, $permission->id);
        $this->assertEquals($this->testEditPermission->name, $permission->name);
        $this->assertEquals($this->testEditPermission->slug, $permission->slug);
    }

    /** @test */
    public function it_is_retrievable_and_created()
    {
        $permissionName = 'Find or Created';
        $permission = app(PermissionContract::class)->findOrCreate($permissionName);

        $this->assertEquals($permissionName, $permission->name);
    }

    /** @test */
    public function it_is_retrievable_created()
    {
        $permissionName = 'Edit Articles';
        $permission = app(PermissionContract::class)->findOrCreate($permissionName);

        $this->assertEquals($permissionName, $permission->name);
    }

    /** @test */
    public function it_is_update_permission()
    {
        $permissionName = 'New Permission Article';
        $permission = app(PermissionContract::class)->findOrCreate($permissionName);

        $this->assertEquals($permissionName, $permission->name);
        $this->assertInstanceOf(PermissionContract::class, $permission);

        $permission->update(['name' => 'Permission Article Edited']);
        $permission->refresh();

        $this->assertEquals('Permission Article Edited', $permission->name);
        $this->assertInstanceOf(PermissionContract::class, $permission);
    }

    /** @test */
    public function it_is_delete_permission()
    {
        $permissionName = 'Delete Permission';
        $permission = app(PermissionContract::class)->findOrCreate($permissionName);

        $this->assertEquals($permissionName, $permission->name);
        $this->assertInstanceOf(PermissionContract::class, $permission);

        $this->assertTrue($permission->delete());
    }

    /** @test */
    public function it_throws_an_exception_when_occurred_error_on_create_permission()
    {
        app('config')->set('code-acl.models.permission.primary_key.name', 'other_id');

        $class = app(PermissionContract::class);

        $this->expectException(PermissionException::class);

        $permission = $class->create(['name' => 'other-test-permission']);
    }
}
