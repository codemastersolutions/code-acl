<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Permission;
use CodeMaster\CodeAcl\Contracts\Role;
use Illuminate\Support\Facades\Artisan;

class CommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_role()
    {
        Artisan::call('code-acl:create-role', ['name' => 'new-role']);

        $this->assertCount(1, app(Role::class)::whereName('new-role')->get());
        $this->assertCount(0, app(Role::class)::whereName('new-role')->first()->permissions);
    }

    /** @test */
    public function it_can_create_a_permission()
    {
        Artisan::call('code-acl:create-permission', ['name' => 'Command Permission']);

        $this->assertCount(1, app(Permission::class)::whereName('Command Permission')->get());
    }

    /** @test */
    public function it_can_create_a_role_without_duplication()
    {
        Artisan::call('code-acl:create-role', ['name' => 'new-role']);
        Artisan::call('code-acl:create-role', ['name' => 'new-role']);

        $this->assertCount(1, app(Role::class)::whereName('new-role')->get());
        $this->assertCount(0, app(Role::class)::whereName('new-role')->first()->permissions);
    }

    /** @test */
    public function it_can_create_a_permission_without_duplication()
    {
        Artisan::call('code-acl:create-permission', ['name' => 'Duplicate Permission']);
        Artisan::call('code-acl:create-permission', ['name' => 'Duplicate Permission']);

        $this->assertCount(1, app(Permission::class)::whereName('Duplicate Permission')->get());
    }

    /** @test */
    public function it_can_reset_cache()
    {
        Artisan::call('code-acl:cache-reset');

        $output = Artisan::output();

        $this->assertFalse(strpos($output, 'Code Acl cache flushed') !== false);
        $this->assertTrue((bool)strpos($output, 'to flush cache') === true);
    }
}
