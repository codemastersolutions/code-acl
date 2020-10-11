<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Role;
use CodeMaster\CodeAcl\Exceptions\RoleAlreadyExists;
use CodeMaster\CodeAcl\Exceptions\RoleDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\RoleException;
use Ramsey\Uuid\Uuid;

class RoleTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_the_role_already_exists()
    {
        $this->expectException(RoleAlreadyExists::class);

        app(Role::class)->create(['name' => 'test-role']);
        app(Role::class)->create(['name' => 'test-role']);
    }

    /** @test */
    public function it_throws_an_exception_when_the_role_not_exists_in_find_by_id()
    {
        $this->expectException(RoleDoesNotExist::class);

        app(Role::class)->findById(Uuid::uuid4());
    }

    /** @test */
    public function it_throws_an_exception_when_the_role_not_exists_in_find_by_name()
    {
        $this->expectException(RoleDoesNotExist::class);

        app(Role::class)->findByName('name not exists');
    }

    /** @test */
    public function it_throws_an_exception_when_the_role_not_exists_in_find_by_slug()
    {
        $this->expectException(RoleDoesNotExist::class);

        app(Role::class)->findBySlug('slug-not-exists');
    }

    /** @test */
    public function it_is_retrievable_by_id()
    {
        $role = app(Role::class)->findById($this->testManangerRole->id);

        $this->assertEquals($this->testManangerRole->id, $role->id);
        $this->assertEquals($this->testManangerRole->name, $role->name);
        $this->assertEquals($this->testManangerRole->slug, $role->slug);
    }

    /** @test */
    public function it_is_retrievable_by_name()
    {
        $role = app(Role::class)->findByName($this->testManangerRole->name);

        $this->assertEquals($this->testManangerRole->id, $role->id);
        $this->assertEquals($this->testManangerRole->name, $role->name);
        $this->assertEquals($this->testManangerRole->slug, $role->slug);
    }

    /** @test */
    public function it_is_retrievable_by_slug()
    {
        $role = app(Role::class)->findBySlug($this->testManangerRole->slug);

        $this->assertEquals($this->testManangerRole->id, $role->id);
        $this->assertEquals($this->testManangerRole->name, $role->name);
        $this->assertEquals($this->testManangerRole->slug, $role->slug);
    }

    /** @test */
    public function it_is_retrievable_and_created()
    {
        $roleName = 'Find or Created';
        $role = app(Role::class)->findOrCreate($roleName);

        $this->assertEquals($roleName, $role->name);
    }

    /** @test */
    public function it_is_retrievable_created()
    {
        $roleName = 'Edit Articles';
        $role = app(Role::class)->findOrCreate($roleName);

        $this->assertEquals($roleName, $role->name);
    }

    /** @test */
    public function it_is_update_role()
    {
        $roleName = 'New Role Article';
        $role = app(Role::class)->findOrCreate($roleName);

        $this->assertEquals($roleName, $role->name);
        $this->assertInstanceOf(Role::class, $role);

        $role->update(['name' => 'Role Article Edited']);
        $role->refresh();

        $this->assertEquals('Role Article Edited', $role->name);
        $this->assertInstanceOf(Role::class, $role);
    }

    /** @test */
    public function it_is_delete_role()
    {
        $roleName = 'Delete Role';
        $role = app(Role::class)->findOrCreate($roleName);

        $this->assertEquals($roleName, $role->name);
        $this->assertInstanceOf(Role::class, $role);

        $this->assertTrue($role->delete());
    }

    /** @test */
    public function it_throws_an_exception_when_occurred_error_on_create_role()
    {
        app('config')->set('code-acl.models.role.primary_key.name', 'other_id');

        $class = app(Role::class);

        $this->expectException(RoleException::class);

        $role = $class->create(['name' => 'other-test-role']);
    }
}
