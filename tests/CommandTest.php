<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Module;
use CodeMaster\CodeAcl\Contracts\Permission;
use CodeMaster\CodeAcl\Contracts\Role;
use CodeMaster\CodeAcl\Contracts\System;
use Illuminate\Support\Facades\Artisan;

class CommandTest extends TestCase
{
    private const ROLE_COMMAND = "code-acl:create-role";
    private const PERMISSION_COMMAND = "code-acl:create-permission";
    private const SYSTEM_COMMAND = "code-acl:create-system";
    private const MODULE_COMMAND = "code-acl:create-module";

    /** @test */
    public function it_can_create_a_role()
    {
        $role = 'new-role';

        Artisan::call(self::ROLE_COMMAND, ['name' => $role]);

        $this->assertCount(1, app(Role::class)::whereName($role)->get());
        $this->assertCount(0, app(Role::class)::whereName($role)->first()->permissions);
    }

    /** @test */
    public function it_can_create_a_system()
    {
        $system = 'new-system';

        Artisan::call(self::SYSTEM_COMMAND, ['name' => $system]);

        $this->assertCount(1, app(System::class)::whereName($system)->get());
    }

    /** @test */
    public function it_can_create_a_module()
    {
        $module = 'new-module';

        Artisan::call(self::MODULE_COMMAND, ['name' => $module]);

        $this->assertCount(1, app(Module::class)::whereName($module)->get());
    }

    /** @test */
    public function it_can_create_a_permission()
    {
        Artisan::call(self::PERMISSION_COMMAND, ['name' => 'Command Permission']);

        $this->assertCount(1, app(Permission::class)::whereName('Command Permission')->get());
    }

    /** @test */
    public function it_can_create_a_role_without_duplication()
    {
        $role = 'new-role';

        Artisan::call(self::ROLE_COMMAND, ['name' => $role]);
        Artisan::call(self::ROLE_COMMAND, ['name' => $role]);

        $this->assertCount(1, app(Role::class)::whereName($role)->get());
        $this->assertCount(0, app(Role::class)::whereName($role)->first()->permissions);
    }

    /** @test */
    public function it_can_create_a_permission_without_duplication()
    {
        $permission = 'Duplicate Permission';

        Artisan::call(self::PERMISSION_COMMAND, ['name' => $permission]);
        Artisan::call(self::PERMISSION_COMMAND, ['name' => $permission]);

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
