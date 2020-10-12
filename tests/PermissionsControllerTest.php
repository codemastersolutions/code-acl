<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Http\Controllers\PermissionsController;
use CodeMaster\CodeAcl\Http\Resources\PermissionsResource;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PermissionControllerTest extends TestCase
{
    /** @test */
    public function it_is_retrieve_permissions_from_index_method()
    {
        $controller = new PermissionsController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(PermissionsResource::class, $response->first());
        $this->assertInstanceOf(PermissionContract::class, $response->first()->first());
    }

    /** @test */
    public function it_is_retrieve_permissions_ordered_by_default_order_from_index_method()
    {
        sleep(1);
        app(PermissionContract::class)->create(['name' => 'Permission Controller']);

        $controller = new PermissionsController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(PermissionsResource::class, $response->first());
        $this->assertInstanceOf(PermissionContract::class, $response->first()->first());
        $this->assertEquals('Permission Controller', $response->first()->name);
    }

    /** @test */
    public function it_is_retrieve_permissions_ordered_by_name_asc_from_index_method()
    {
        app('config')->set('code-acl.models.permission.meta_data.order_by', ['field' => 'name', 'direction' => 'asc']);

        $controller = new PermissionsController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(PermissionsResource::class, $response->first());
        $this->assertInstanceOf(PermissionContract::class, $response->first()->first());
        $this->assertEquals('Delete Articles', $response->where('slug', 'delete-articles')->first()->name);
        $this->assertEquals('Delete', $response->first()->name);
    }

    /** @test */
    public function it_is_retrieve_permissions_paginated_per_page_3_from_index_method()
    {
        app('config')->set('code-acl.models.permission.meta_data.pagination.per_page', 3);

        $controller = new PermissionsController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(PermissionsResource::class, $response->first());
        $this->assertInstanceOf(PermissionContract::class, $response->first()->first());
        $this->assertCount(3, $response);
    }

    /** @test */
    public function it_throws_an_exception_when_the_config_not_loaded()
    {
        $this->expectException(Exception::class);

        app('config')->set('code-acl.models.permission.meta_data', []);

        $controller = new PermissionsController();

        $this->assertInstanceOf(PermissionsController::class, $controller);
    }
}
