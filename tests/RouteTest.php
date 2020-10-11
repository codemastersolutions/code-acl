<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use Ramsey\Uuid\Uuid;

class RouteTest extends TestCase
{
    private string $urlPermissions, $urlRoles, $urlUsers;

    public function setUp(): void
    {
        parent::setUp();

        $urlPath = config('code-acl.defaults.code-acl.path');
        $this->urlPermissions = "api/{$urlPath}/permissions/";
        $this->urlRoles = "api/{$urlPath}/roles/";
        $this->urlUsers = "api/{$urlPath}/users/";
    }

    /** @test */
    public function it_is_can_retrieve_permissions_from_route_index()
    {
        $response = $this->get($this->urlPermissions, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertCount(12, $response['data']);
    }

    /** @test */
    public function it_is_can_retrieve_permission_from_route_show_with_id()
    {
        $permission = app(PermissionContract::class)->first();

        $response = $this->get($this->urlPermissions.$permission->id);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertContains($permission->name, $response['data']);
        $this->assertContains($permission->slug, $response['data']);
        $this->assertContains($permission->id, $response['data']);
    }

    /** @test */
    public function it_is_can_retrieve_permission_from_route_show_with_slug()
    {
        $permission = app(PermissionContract::class)->first();

        $response = $this->get($this->urlPermissions.$permission->slug);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertContains($permission->name, $response['data']);
        $this->assertContains($permission->slug, $response['data']);
        $this->assertContains($permission->id, $response['data']);
    }

    /** @test */
    public function it_is_can_update_a_permission_from_route_update()
    {
        $permission = app(PermissionContract::class)->findByName('Insert Articles');

        $response = $this->put(
            $this->urlPermissions.$permission->slug,
            ['name' => 'New Insert Articles Name'],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(202);
        $response->assertSuccessful();
        $this->assertEquals('New Insert Articles Name', $response->json('name'));
        $this->assertEquals($permission->slug, $response->json('slug'));
        $this->assertEquals($permission->id, $response->json('id'));
    }

    /** @test */
    public function it_is_can_create_a_permission_from_route_store()
    {
        $response = $this->post(
            $this->urlPermissions,
            ['name' => 'New Permission Test'],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(201);
        $response->assertCreated();
        $response->assertSuccessful();
        $this->assertEquals('New Permission Test', $response->json('name'));
        $this->assertTrue(Uuid::isValid($response->json('id')));
    }

    /** @test */
    public function it_is_can_delete_a_permission_from_route_delete()
    {
        $permission = app(PermissionContract::class)->findOrCreate('New Permission Test');

        $response = $this->delete(
            $this->urlPermissions.$permission->slug,
            [],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_get_a_not_found_response_from_routes()
    {
        $response = $this->get($this->urlPermissions.'/slug-not-exits');
        $response->assertStatus(404);
        $response->assertNotFound();

        $response = $this->put($this->urlPermissions.'/slug-not-exits');
        $response->assertStatus(404);
        $response->assertNotFound();

        $response = $this->delete($this->urlPermissions.'/slug-not-exits');
        $response->assertStatus(404);
        $response->assertNotFound();
    }

    /** @test */
    public function it_is_get_a_error_if_max_length_name_was_exceded()
    {
        $response = $this->post(
            $this->urlPermissions,
            ['name' => 'New Permission Test New Permission Test New Permission Test New Permission Test New Permission Test New Permission Test'],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_is_get_a_404_error_if_permission_does_not_exists()
    {
        $response = $this->get($this->urlPermissions.'permission-does-not-exists');
        $response->assertStatus(404);
        $response->assertNotFound();
    }

    /*** End Permissions Routes ***/

    /*** Roles Routes ***/

    /** @test */
    public function it_is_can_retrieve_roles_from_route_index()
    {
        $response = $this->get($this->urlRoles);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertEquals('Creator', $response['data'][0]['name']);
    }

    /** @test */
    public function it_is_can_retrieve_role_from_route_show_with_id()
    {
        $role = app(RoleContract::class)->first();

        $response = $this->get($this->urlRoles.$role->id);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertContains($role->name, $response['data']);
        $this->assertContains($role->slug, $response['data']);
        $this->assertContains($role->id, $response['data']);
    }

    /** @test */
    public function it_is_can_retrieve_role_from_route_show_with_slug()
    {
        $role = app(RoleContract::class)->first();

        $response = $this->get($this->urlRoles.$role->slug);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertContains($role->name, $response['data']);
        $this->assertContains($role->slug, $response['data']);
        $this->assertContains($role->id, $response['data']);
    }

    /** @test */
    public function it_is_can_update_a_role_from_route_update()
    {
        $role = app(RoleContract::class)->findByName('Creator');

        $response = $this->put(
            $this->urlRoles.$role->slug,
            ['name' => 'New Creator Name'],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(202);
        $response->assertSuccessful();
        $this->assertEquals('New Creator Name', $response->json('name'));
        $this->assertEquals($role->slug, $response->json('slug'));
        $this->assertEquals($role->id, $response->json('id'));
    }

    /** @test */
    public function it_is_can_create_a_role_from_route_store()
    {
        $response = $this->post(
            $this->urlRoles,
            ['name' => 'New Role Test'],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(201);
        $response->assertCreated();
        $response->assertSuccessful();
        $this->assertEquals('New Role Test', $response->json('name'));
        $this->assertTrue(Uuid::isValid($response->json('id')));
    }

    /** @test */
    public function it_is_can_delete_a_role_from_route_delete()
    {
        $role = app(RoleContract::class)->findOrCreate('New Role Test');

        $response = $this->delete(
            $this->urlRoles.$role->slug,
            [],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_get_a_not_found_response_from_role_routes()
    {
        $response = $this->get($this->urlRoles.'/slug-not-exits');
        $response->assertStatus(404);
        $response->assertNotFound();

        $response = $this->put($this->urlRoles.'/slug-not-exits');
        $response->assertStatus(404);
        $response->assertNotFound();

        $response = $this->delete($this->urlRoles.'/slug-not-exits');
        $response->assertStatus(404);
        $response->assertNotFound();
    }

    /** @test */
    public function it_is_get_a_error_if_max_length_role_name_was_exceded()
    {
        $response = $this->post(
            $this->urlRoles,
            ['name' => 'New Role Test New Role Test New Role Test New Role Test New Role Test New Role Test'],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_is_get_a_404_error_if_role_does_not_exists()
    {
        $response = $this->get($this->urlRoles.'role-does-not-exists');
        $response->assertStatus(404);
        $response->assertNotFound();
    }

    /*** End Roles Routes ***/

    /*** Begin Users Routes ***/

    /** @test */
    public function it_is_give_permissions_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(201);
        $response->assertSuccessful();
        $this->assertTrue($response['result']);
    }

    /** @test */
    public function it_is_revoke_permissions_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_permissions_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'permissions' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_permissions_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'permissions' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_permissions_data()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_permissions_data()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_give_roles_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'roles' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(201);
        $response->assertCreated();
        $response->assertSuccessful();
        $this->assertTrue($response['result']);
    }

    /** @test */
    public function it_is_revoke_roles_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'roles' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(204);
        $response->assertNoContent();
        $response->assertSuccessful();
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_roles_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_roles_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_roles_data()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_roles_data()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_give_permissions_to_a_role()
    {
        $response = $this->post(
            "{$this->urlRoles}{$this->testCreatorRole->id}/permissions",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(201);
        $response->assertSuccessful();
        $this->assertTrue($response['result']);
    }

    /** @test */
    public function it_is_revoke_permissions_to_a_role()
    {
        $response = $this->delete(
            "{$this->urlRoles}{$this->testCreatorRole->id}/permissions",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_permissions_to_a_role()
    {
        $response = $this->post(
            "{$this->urlRoles}{$this->testCreatorRole->id}/permissions",
            [
                'permissions' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_permissions_to_a_role()
    {
        $response = $this->delete(
            "{$this->urlRoles}{$this->testCreatorRole->id}/permissions",
            [
                'permissions' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_permissions_data_to_a_role()
    {
        $response = $this->post(
            "{$this->urlRoles}{$this->testCreatorRole->id}/permissions",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_permissions_data_to_a_role()
    {
        $response = $this->delete(
            "{$this->urlRoles}{$this->testCreatorRole->id}/permissions",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_can_get_permissions_attached_to_a_user()
    {
        $response = $this->get(
            "{$this->urlUsers}{$this->testUser1->id}/permissions",
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertSuccessful();
    }

    /** @test */
    public function it_is_can_get_roles_attached_to_a_user()
    {
        $response = $this->get(
            "{$this->urlUsers}{$this->testUser1->id}/roles",
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertSuccessful();
    }

    /** @test */
    public function it_is_can_get_permissions_attached_to_a_role()
    {
        $response = $this->get(
            "{$this->urlRoles}{$this->testManangerRole->id}/permissions",
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertSuccessful();
    }

    /*** End Roles Routes ***/
}
