<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use CodeMaster\CodeAcl\Http\Controllers\ModulesController;
use CodeMaster\CodeAcl\Http\Resources\ModulesResource;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ModulesControllerTest extends TestCase
{
    /** @test */
    public function it_is_retrieve_modules_from_index_method()
    {
        $controller = new ModulesController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(ModulesResource::class, $response->first());
        $this->assertInstanceOf(ModuleContract::class, $response->first()->first());
    }

    /** @test */
    public function it_is_retrieve_modules_ordered_by_default_order_from_index_method()
    {
        sleep(1);
        app(ModuleContract::class)->create(['name' => 'Module Controller']);

        $controller = new ModulesController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(ModulesResource::class, $response->first());
        $this->assertInstanceOf(ModuleContract::class, $response->first()->first());
        $this->assertEquals('Module Controller', $response->first()->name);
    }

    /** @test */
    public function it_is_retrieve_modules_ordered_by_name_asc_from_index_method()
    {
        app('config')->set('code-acl.models.module.meta_data.order_by', ['field' => 'name', 'direction' => 'asc']);

        $controller = new ModulesController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(ModulesResource::class, $response->first());
        $this->assertInstanceOf(ModuleContract::class, $response->first()->first());
        $this->assertEquals('New Module', $response->where('slug', 'new-module')->first()->name);
        $this->assertEquals('New Module', $response->first()->name);
    }

    /** @test */
    public function it_is_retrieve_modules_paginated_per_page_3_from_index_method()
    {
        app('config')->set('code-acl.models.module.meta_data.pagination.per_page', 1);

        $controller = new ModulesController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(ModulesResource::class, $response->first());
        $this->assertInstanceOf(ModuleContract::class, $response->first()->first());
        $this->assertCount(1, $response);
    }

    /** @test */
    public function it_is_retrieve_modules_without_paginate_from_index_method()
    {
        app('config')->set('code-acl.models.module.meta_data.pagination.per_page', null);

        $controller = new ModulesController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(ModulesResource::class, $response->first());
        $this->assertInstanceOf(ModuleContract::class, $response->first()->first());
    }

    /** @test */
    public function it_throws_an_exception_when_the_config_not_loaded()
    {
        $this->expectException(Exception::class);

        app('config')->set('code-acl.models.module.meta_data', []);

        $controller = new ModulesController();

        $this->assertInstanceOf(ModulesController::class, $controller);
    }
}
