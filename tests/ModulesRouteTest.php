<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use Ramsey\Uuid\Uuid;

class ModulesRouteTest extends TestCase
{
    private string $urlModules, $newModuleTest, $slugDoesNotExist, $accept;

    public function setUp(): void
    {
        parent::setUp();

        $urlPath = config('code-acl.defaults.code-acl.path');
        $this->urlModules = "api/{$urlPath}/modules/";
        $this->newModuleTest = "New Module Test";
        $this->slugDoesNotExist = "/slug-not-exits";
        $this->accept = "application/json";
    }

    /** @test */
    public function it_is_can_retrieve_modules_from_route_index()
    {
        $response = $this->get($this->urlModules);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertEquals('New Module 1', $response['data'][0]['name']);
    }

    /** @test */
    public function it_is_can_retrieve_module_from_route_show_with_id()
    {
        $module = app(ModuleContract::class)->first();

        $response = $this->get($this->urlModules.$module->id);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertContains($module->name, $response['data']);
        $this->assertContains($module->slug, $response['data']);
        $this->assertContains($module->id, $response['data']);
    }

    /** @test */
    public function it_is_can_retrieve_module_from_route_show_with_slug()
    {
        $module = app(ModuleContract::class)->first();

        $response = $this->get($this->urlModules.$module->slug);
        $response->assertStatus(200);
        $response->assertSuccessful();
        $this->assertContains($module->name, $response['data']);
        $this->assertContains($module->slug, $response['data']);
        $this->assertContains($module->id, $response['data']);
    }

    /** @test */
    public function it_is_can_update_a_module_from_route_update()
    {
        $module = app(ModuleContract::class)->findByName('New Module');

        $response = $this->put(
            $this->urlModules.$module->slug,
            ['name' => 'New Module Name'],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(202);
        $response->assertSuccessful();
        $this->assertEquals('New Module Name', $response->json('name'));
        $this->assertEquals($module->slug, $response->json('slug'));
        $this->assertEquals($module->id, $response->json('id'));
    }

    /** @test */
    public function it_is_can_create_a_module_from_route_store()
    {
        $response = $this->post(
            $this->urlModules,
            ['name' => $this->newModuleTest],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(201);
        $response->assertCreated();
        $response->assertSuccessful();
        $this->assertEquals($this->newModuleTest, $response->json('name'));
        $this->assertTrue(Uuid::isValid($response->json('id')));
    }

    /** @test */
    public function it_is_can_delete_a_module_from_route_delete()
    {
        $module = app(ModuleContract::class)->findOrCreate($this->newModuleTest);

        $response = $this->delete(
            $this->urlModules.$module->slug,
            [],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_get_a_not_found_response_from_module_routes()
    {
        $response = $this->get($this->urlModules.$this->slugDoesNotExist);
        $response->assertStatus(404);
        $response->assertNotFound();

        $response = $this->put($this->urlModules.$this->slugDoesNotExist);
        $response->assertStatus(404);
        $response->assertNotFound();

        $response = $this->delete($this->urlModules.$this->slugDoesNotExist);
        $response->assertStatus(404);
        $response->assertNotFound();
    }

    /** @test */
    public function it_is_get_a_error_if_max_length_module_name_was_exceded()
    {
        $response = $this->post(
            $this->urlModules,
            ['name' => 'New Module Test New Module Test New Module Test New Module Test New Module Test New Module Test'],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function it_is_get_a_404_error_if_module_does_not_exists()
    {
        $response = $this->get($this->urlModules.'module-does-not-exists');
        $response->assertStatus(404);
        $response->assertNotFound();
    }
}
