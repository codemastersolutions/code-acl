<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use Ramsey\Uuid\Uuid;

class PermissionsRouteTest extends TestCase
{
    private string $urlPermissions;

    public function setUp(): void
    {
        parent::setUp();

        $urlPath = config('code-acl.defaults.code-acl.path');
        $this->urlPermissions = "api/{$urlPath}/permissions/";
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
}
