<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Test\User;
use CodeMaster\CodeAcl\Contracts\Permission;
use CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

class HasPermissionsTest extends TestCase
{
    /** @test */
    public function it_check_if_permission_exists_by_has_direct_permission_by_permission_name()
    {
        $this->assertTrue($this->testUser->hasDirectPermission($this->testEditPermission->name));
    }

    /** @test */
    public function it_check_if_permission_exists_by_has_direct_permission_by_permission_id()
    {
        $this->assertTrue($this->testUser->hasDirectPermission($this->testEditPermission->id));
    }

    /** @test */
    public function it_check_if_permission_exists_by_has_direct_permission_by_permission_slug()
    {
        $this->assertTrue($this->testUser->hasDirectPermission($this->testEditPermission->slug));
    }

    /** @test */
    public function it_doesnt_detach_permissions_when_soft_deleting()
    {
        SoftDeletingUser::create(['email' => 'test@example.com']);

        $user = SoftDeletingUser::whereEmail('test@example.com')->first();
        $user->givePermissions(['insert-news']);
        $user->delete();

        $user = SoftDeletingUser::withTrashed()->find($user->id);

        $this->assertTrue($user->hasPermission('insert-news'));
    }

    // /** @test */
    // public function it_does_detach_permissions_when_isnt_soft_deleting()
    // {
    //     User::create(['id' => Uuid::uuid4(), 'email' => 'test33@example.com']);

    //     $user = User::whereEmail('test33@example.com')->first();
    //     $user->givePermissions(['insert-news']);
    //     $userId = $user->id;
    //     $user->delete();

    //     $this->expectException(PermissionDoesNotExist::class);

    //     app(PermissionPivot::class)->findByRelationshipId($userId);
    // }

    /** @test */
    public function it_can_revoke_all_permissions_from_a_user()
    {
        $user = $this->testUser->revokeAllPermissions();
        $this->assertInstanceOf(User::class, $user);

        $this->assertCount(0, $this->testUser->permissions);
        $this->assertCount(0, $user->permissions);
    }

    /** @test */
    public function it_can_get_all_permissions_from_a_user()
    {
        $this->testUser->revokeAllPermissions();

        $insert = $this->testUser->givePermissions($this->testInsertPermission->name);
        $edit = $this->testUser->givePermissions($this->testEditPermission->id);
        $delete = $this->testUser->givePermissions($this->testDeletePermission->slug);

        $permissions = $this->testUser->getAllPermissions();

        $this->assertInstanceOf(Collection::class, $permissions);
        $this->assertTrue(
            $this->testUser
                ->hasAllPermissions($this->testInsertPermission, $this->testEditPermission, $this->testDeletePermission)
        );
    }

    /** @test */
    public function it_can_add_a_permission_to_a_user()
    {
        $newPermission = app(Permission::class)->create(['name' => 'New Permission']);

        $this->testUser->givePermissions($newPermission);

        $this->assertTrue($this->testUser->checkPermission($newPermission));
    }

    /** @test */
    public function it_can_convert_collection_of_permissions_to_permission_models()
    {
        $permissions = app(Permission::class)::all();

        $permissionsArray = app(User::class)->convertToModels($permissions);

        $this->assertIsArray($permissionsArray);
    }

    /** @test */
    public function it_can_assign_a_permission_to_a_user()
    {
        $permission = app(Permission::class)->create(['name' => 'Test Permission']);
        $permission2 = app(Permission::class)->create(['name' => 'Other Test Permission']);

        $this->assertInstanceOf(Permission::class, $permission);
        $this->assertInstanceOf(Permission::class, $permission2);

        $this->testUser->givePermissions($permission, $permission2);

        $this->assertTrue($this->testUser->checkPermission($permission));
        $this->assertTrue($this->testUser->checkPermission($permission2));
        $this->assertTrue($this->testUser->hasAllPermissions($permission, $permission2));
    }

    /** @test */
    public function it_can_revoke_a_permission_from_a_user()
    {
        $this->testUser->givePermissions($this->testInsertPermission);

        $this->assertTrue($this->testUser->checkPermission($this->testInsertPermission));

        $this->testUser->revokePermission($this->testInsertPermission);

        $this->assertFalse($this->testUser->checkPermission($this->testInsertPermission));
    }

    /** @test */
    public function it_can_revoke_permissions_from_a_user()
    {
        $this->testUser->givePermissions($this->testInsertPermission, $this->testDeletePermission);

        $this->assertTrue($this->testUser->hasAllPermissions($this->testInsertPermission, $this->testDeletePermission));

        $this->testUser->revokePermissions($this->testInsertPermission, $this->testDeletePermission);

        $this->assertFalse($this->testUser->checkPermission($this->testInsertPermission));
        $this->assertFalse($this->testUser->checkPermission($this->testDeletePermission));
        $this->assertFalse($this->testUser->hasAllPermissions($this->testInsertPermission, $this->testDeletePermission));
    }

    /** @test */
    public function it_can_scope_users_without_permissions_only_permission()
    {
        $this->testUser->givePermissions('Edit News');
        $this->testUser1->givePermissions('Edit Articles', 'Edit News');

        $scopedUsers = User::permission('Edit News')->get();

        $this->assertEquals($scopedUsers->count(), 2);
        $this->assertCount(2, $scopedUsers);
    }

    /** @test */
    public function it_is_check_if_permission_not_assigned_to_a_user()
    {
        $anotherPermission = app(Permission::class)->create(['name' => 'Another Permission']);
        $permission = clone $anotherPermission;
        $anotherPermission->delete();
        $this->assertFalse($this->testUser->checkPermission($permission->slug));
    }

    /** @test */
    public function it_throws_an_exception_when_the_permission_not_was_stored()
    {
        $this->expectException(PermissionDoesNotExist::class);

        $this->testUser->givePermissions('non-existing-permission');
    }

    /** @test */
    public function it_is_get_permission_names()
    {
        $permissionNames = $this->testUser->getPemissionsName();

        $this->assertInstanceOf(Collection::class, $permissionNames);
        $this->assertContains('Insert Articles', $permissionNames);
    }

    /** @test */
    public function it_is_check_if_has_all_direct_permissions()
    {
        $hasAll = $this->testUser->hasAllDirectPermissions($this->testInsertPermission, $this->testUpdatePermission);

        $this->assertTrue($hasAll);
    }

    /** @test */
    public function it_is_check_if_not_has_all_direct_permissions()
    {
        $hasAll = $this->testUser->hasAllDirectPermissions($this->testInsertNews, $this->testEditNews);

        $this->assertFalse($hasAll);
    }

    /** @test */
    public function it_is_check_if_has_any_permission()
    {
        $hasAll = $this->testUser->hasAnyPermission($this->testInsertPermission, $this->testEditNews);

        $this->assertTrue($hasAll);
    }

    /** @test */
    public function it_is_check_if_not_has_any_permission()
    {
        $hasAll = $this->testUser->hasAnyPermission($this->testInsertNews, $this->testEditNews);

        $this->assertFalse($hasAll);
    }
}
