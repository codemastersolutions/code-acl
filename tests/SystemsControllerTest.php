<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use CodeMaster\CodeAcl\Http\Controllers\SystemsController;
use CodeMaster\CodeAcl\Http\Resources\SystemsResource;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SystemsControllerTest extends TestCase
{
    /** @test */
    public function it_is_retrieve_systems_from_index_method()
    {
        $controller = new SystemsController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(SystemsResource::class, $response->first());
        $this->assertInstanceOf(SystemContract::class, $response->first()->first());
    }

    /** @test */
    public function it_is_retrieve_systems_ordered_by_default_order_from_index_method()
    {
        sleep(1);
        app(SystemContract::class)->create(['name' => 'System Controller']);

        $controller = new SystemsController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(SystemsResource::class, $response->first());
        $this->assertInstanceOf(SystemContract::class, $response->first()->first());
        $this->assertEquals('System Controller', $response->first()->name);
    }

    /** @test */
    public function it_is_retrieve_systems_ordered_by_name_asc_from_index_method()
    {
        app('config')->set('code-acl.models.system.meta_data.order_by', ['field' => 'name', 'direction' => 'asc']);

        $controller = new SystemsController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(SystemsResource::class, $response->first());
        $this->assertInstanceOf(SystemContract::class, $response->first()->first());
        $this->assertEquals('New System', $response->where('slug', 'new-system')->first()->name);
        $this->assertEquals('New System', $response->first()->name);
    }

    /** @test */
    public function it_is_retrieve_systems_paginated_per_page_3_from_index_method()
    {
        app('config')->set('code-acl.models.system.meta_data.pagination.per_page', 1);

        $controller = new SystemsController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(SystemsResource::class, $response->first());
        $this->assertInstanceOf(SystemContract::class, $response->first()->first());
        $this->assertCount(1, $response);
    }

    /** @test */
    public function it_is_retrieve_systems_without_paginate_from_index_method()
    {
        app('config')->set('code-acl.models.system.meta_data.pagination.per_page', null);

        $controller = new SystemsController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(SystemsResource::class, $response->first());
        $this->assertInstanceOf(SystemContract::class, $response->first()->first());
    }

    /** @test */
    public function it_throws_an_exception_when_the_config_not_loaded()
    {
        $this->expectException(Exception::class);

        app('config')->set('code-acl.models.system.meta_data', []);

        $controller = new SystemsController();

        $this->assertInstanceOf(SystemsController::class, $controller);
    }
}
