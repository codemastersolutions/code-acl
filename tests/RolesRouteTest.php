<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use Ramsey\Uuid\Uuid;

class RolesRouteTest extends TestCase
{
    private string $urlRoles, $newRoleTest, $slugDoesNotExist, $accept, $pathPermissions;

    public function setUp(): void
    {
        parent::setUp();

        $urlPath = config('code-acl.defaults.code-acl.path');
        $this->urlRoles = "api/{$urlPath}/roles/";
        $this->newRoleTest = "New Role Test";
        $this->slugDoesNotExist = "/slug-not-exits";
        $this->accept = "application/json";
        $this->pathPermissions = "/permissions";
    }

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
            ['Accept' => $this->accept]
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
            ['name' => $this->newRoleTest],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(201);
        $response->assertCreated();
        $response->assertSuccessful();
        $this->assertEquals($this->newRoleTest, $response->json('name'));
        $this->assertTrue(Uuid::isValid($response->json('id')));
    }

    /** @test */
    public function it_is_can_delete_a_role_from_route_delete()
    {
        $role = app(RoleContract::class)->findOrCreate($this->newRoleTest);

        $response = $this->delete(
            $this->urlRoles.$role->slug,
            [],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_get_a_not_found_response_from_role_routes()
    {
        $response = $this->get($this->urlRoles.$this->slugDoesNotExist);
        $response->assertStatus(404);
        $response->assertNotFound();

        $response = $this->put($this->urlRoles.$this->slugDoesNotExist);
        $response->assertStatus(404);
        $response->assertNotFound();

        $response = $this->delete($this->urlRoles.$this->slugDoesNotExist);
        $response->assertStatus(404);
        $response->assertNotFound();
    }

    /** @test */
    public function it_is_get_a_error_if_max_length_role_name_was_exceded()
    {
        $response = $this->post(
            $this->urlRoles,
            ['name' => 'New Role Test New Role Test New Role Test New Role Test New Role Test New Role Test'],
            ['Accept' => $this->accept]
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

    /** @test */
    public function it_is_get_failure_with_give_non_exists_permissions_data_to_a_role()
    {
        $response = $this->post(
            "{$this->urlRoles}{$this->testCreatorRole->id}{$this->pathPermissions}",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_can_get_permissions_attached_to_a_role()
    {
        $response = $this->get(
            "{$this->urlRoles}{$this->testManangerRole->id}{$this->pathPermissions}",
            ['Accept' => $this->accept]
        );

        $response->assertStatus(200);
        $response->assertSuccessful();
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_permissions_data_to_a_role()
    {
        $response = $this->delete(
            "{$this->urlRoles}{$this->testCreatorRole->id}{$this->pathPermissions}",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_give_permissions_to_a_role()
    {
        $response = $this->post(
            "{$this->urlRoles}{$this->testCreatorRole->id}{$this->pathPermissions}",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(201);
        $response->assertSuccessful();
        $this->assertTrue($response['result']);
    }

    /** @test */
    public function it_is_revoke_permissions_to_a_role()
    {
        $response = $this->delete(
            "{$this->urlRoles}{$this->testCreatorRole->id}{$this->pathPermissions}",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_permissions_to_a_role()
    {
        $response = $this->post(
            "{$this->urlRoles}{$this->testCreatorRole->id}{$this->pathPermissions}",
            [
                'permissions' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_permissions_to_a_role()
    {
        $response = $this->delete(
            "{$this->urlRoles}{$this->testCreatorRole->id}{$this->pathPermissions}",
            [
                'permissions' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }
}
