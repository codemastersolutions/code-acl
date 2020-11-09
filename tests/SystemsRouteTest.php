<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use Ramsey\Uuid\Uuid;

class SystemsRouteTest extends TestCase
{
    private string $urlSystems, $newSystemTest, $slugDoesNotExist, $accept;

    public function setUp(): void
    {
        parent::setUp();

        $urlPath = config('code-acl.defaults.code-acl.path');
        $this->urlSystems = "api/{$urlPath}/systems/";
        $this->newSystemTest = "New System Test";
        $this->slugDoesNotExist = "/slug-not-exits";
        $this->accept = "application/json";
    }

    /** @test */
    public function it_is_can_retrieve_systems_from_route_index()
    {
        $response = $this->get($this->urlSystems);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertEquals('New System 1', $response['data'][0]['name']);
    }

    /** @test */
    public function it_is_can_retrieve_system_from_route_show_with_id()
    {
        $system = app(SystemContract::class)->first();

        $response = $this->get($this->urlSystems.$system->id);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertContains($system->name, $response['data']);
        $this->assertContains($system->slug, $response['data']);
        $this->assertContains($system->id, $response['data']);
    }

    /** @test */
    public function it_is_can_retrieve_system_from_route_show_with_slug()
    {
        $system = app(SystemContract::class)->first();

        $response = $this->get($this->urlSystems.$system->slug);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertContains($system->name, $response['data']);
        $this->assertContains($system->slug, $response['data']);
        $this->assertContains($system->id, $response['data']);
    }

    /** @test */
    public function it_is_can_update_a_system_from_route_update()
    {
        $system = app(SystemContract::class)->findByName('New System');

        $response = $this->put(
            $this->urlSystems.$system->slug,
            ['name' => 'New System Name'],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(202);
        $response->assertSuccessful();
        $this->assertEquals('New System Name', $response->json('name'));
        $this->assertEquals($system->slug, $response->json('slug'));
        $this->assertEquals($system->id, $response->json('id'));
    }

    /** @test */
    public function it_is_can_create_a_system_from_route_store()
    {
        $response = $this->post(
            $this->urlSystems,
            ['name' => $this->newSystemTest],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(201);
        $response->assertCreated();
        $response->assertSuccessful();
        $this->assertEquals($this->newSystemTest, $response->json('name'));
        $this->assertTrue(Uuid::isValid($response->json('id')));
    }

    /** @test */
    public function it_is_can_delete_a_system_from_route_delete()
    {
        $system = app(SystemContract::class)->findOrCreate($this->newSystemTest);

        $response = $this->delete(
            $this->urlSystems.$system->slug,
            [],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_get_a_not_found_response_from_system_routes()
    {
        $response = $this->get($this->urlSystems.$this->slugDoesNotExist);
        $response->assertStatus(404);
        $response->assertNotFound();

        $response = $this->put($this->urlSystems.$this->slugDoesNotExist);
        $response->assertStatus(404);
        $response->assertNotFound();

        $response = $this->delete($this->urlSystems.$this->slugDoesNotExist);
        $response->assertStatus(404);
        $response->assertNotFound();
    }

    /** @test */
    public function it_is_get_a_error_if_max_length_system_name_was_exceded()
    {
        $response = $this->post(
            $this->urlSystems,
            ['name' => 'New System Test New System Test New System Test New System Test New System Test New System Test'],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_is_get_a_404_error_if_system_does_not_exists()
    {
        $response = $this->get($this->urlSystems.'system-does-not-exists');
        $response->assertStatus(404);
        $response->assertNotFound();
    }
}
