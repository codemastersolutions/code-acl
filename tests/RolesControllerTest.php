<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Http\Controllers\RolesController;
use CodeMaster\CodeAcl\Http\Resources\RolesResource;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RolesControllerTest extends TestCase
{
    /** @test */
    public function it_is_retrieve_roles_from_index_method()
    {
        $controller = new RolesController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(RolesResource::class, $response->first());
        $this->assertInstanceOf(RoleContract::class, $response->first()->first());
    }

    /** @test */
    public function it_is_retrieve_roles_ordered_by_default_order_from_index_method()
    {
        sleep(1);
        app(RoleContract::class)->create(['name' => 'Role Controller']);

        $controller = new RolesController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(RolesResource::class, $response->first());
        $this->assertInstanceOf(RoleContract::class, $response->first()->first());
        $this->assertEquals('Role Controller', $response->first()->name);
    }

    /** @test */
    public function it_is_retrieve_roles_ordered_by_name_asc_from_index_method()
    {
        app('config')->set('code-acl.models.role.meta_data.order_by', ['field' => 'name', 'direction' => 'asc']);

        $controller = new RolesController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(RolesResource::class, $response->first());
        $this->assertInstanceOf(RoleContract::class, $response->first()->first());
        $this->assertEquals('Creator', $response->first()->name);
    }

    /** @test */
    public function it_is_retrieve_roles_paginated_per_page_3_from_index_method()
    {
        app('config')->set('code-acl.models.role.meta_data.pagination.per_page', 3);

        $controller = new RolesController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(RolesResource::class, $response->first());
        $this->assertInstanceOf(RoleContract::class, $response->first()->first());
        $this->assertCount(3, $response);
    }

    /** @test */
    public function it_is_retrieve_roles_without_paginate_from_index_method()
    {
        app('config')->set('code-acl.models.role.meta_data.pagination.per_page', null);

        $controller = new RolesController();

        $response = $controller->index();

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertInstanceOf(RolesResource::class, $response->first());
        $this->assertInstanceOf(RoleContract::class, $response->first()->first());
    }

    /** @test */
    public function it_throws_an_exception_when_the_config_not_loaded()
    {
        $this->expectException(Exception::class);

        app('config')->set('code-acl.models.role.meta_data', []);

        $controller = new RolesController();

        $this->assertInstanceOf(RolesController::class, $controller);
    }
}
