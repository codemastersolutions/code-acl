<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Permission;
use CodeMaster\CodeAcl\Contracts\Role;
use Illuminate\Support\Facades\Artisan;

class CommandTest extends TestCase
{
    private const roleCommand = "code-acl:create-role";
    private const permissionCommand = "code-acl:create-permission";

    /** @test */
    public function it_can_create_a_role()
    {
        $role = 'new-role';

        Artisan::call(self::roleCommand, ['name' => $role]);

        $this->assertCount(1, app(Role::class)::whereName($role)->get());
        $this->assertCount(0, app(Role::class)::whereName($role)->first()->permissions);
    }

    /** @test */
    public function it_can_create_a_permission()
    {
        Artisan::call(self::permissionCommand, ['name' => 'Command Permission']);

        $this->assertCount(1, app(Permission::class)::whereName('Command Permission')->get());
    }

    /** @test */
    public function it_can_create_a_role_without_duplication()
    {
        $role = 'new-role';

        Artisan::call(self::roleCommand, ['name' => $role]);
        Artisan::call(self::roleCommand, ['name' => $role]);

        $this->assertCount(1, app(Role::class)::whereName($role)->get());
        $this->assertCount(0, app(Role::class)::whereName($role)->first()->permissions);
    }

    /** @test */
    public function it_can_create_a_permission_without_duplication()
    {
        $permission = 'Duplicate Permission';

        Artisan::call(self::permissionCommand, ['name' => $permission]);
        Artisan::call(self::permissionCommand, ['name' => $permission]);

        $this->assertCount(1, app(Permission::class)::whereName($permission)->get());
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
