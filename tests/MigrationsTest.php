<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Module;
use CodeMaster\CodeAcl\Contracts\Permission;
use CodeMaster\CodeAcl\Contracts\Role;
use CodeMaster\CodeAcl\Contracts\System;
use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;

class MigrationsTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_config_does_not_exists_for_modules_table()
    {
        $this->expectException(ConfigNotLoaded::class);

        app('config')->set('code-acl.models.module', null);

        include_once __DIR__.'/../src/Database/Migrations/2014_10_12_113000_create_modules_tables.php';

        (new \CreateModulesTables())->up();
    }

    /** @test */
    public function it_throws_an_exception_when_config_does_not_exists_for_permissions_table()
    {
        $this->expectException(ConfigNotLoaded::class);

        app('config')->set('code-acl.models.permission', null);

        include_once __DIR__.'/../src/Database/Migrations/2014_10_12_111000_create_permissions_tables.php';

        (new \CreatePermissionsTables())->up();
    }

    /** @test */
    public function it_throws_an_exception_when_config_does_not_exists_for_roles_table()
    {
        $this->expectException(ConfigNotLoaded::class);

        app('config')->set('code-acl.models.role', null);

        include_once __DIR__.'/../src/Database/Migrations/2014_10_12_112000_create_roles_tables.php';

        (new \CreateRolesTables())->up();
    }

    /** @test */
    public function it_throws_an_exception_when_config_does_not_exists_for_systems_table()
    {
        $this->expectException(ConfigNotLoaded::class);

        app('config')->set('code-acl.models.system', null);

        include_once __DIR__.'/../src/Database/Migrations/2014_10_12_114000_create_systems_tables.php';

        (new \CreateSystemsTables())->up();
    }

    /** @test */
    public function it_test_field_option_for_permissions_table()
    {
        app('config')->set('code-acl.models.permission.table', 'permissions_test');
        app('config')->set('code-acl.models.user_has_permission.table', 'permissions_user_test');
        app('config')->set('code-acl.models.permission.primary_key.type', 'number');
        app('config')->set('code-acl.models.user_has_permission.permission_key.type', 'number');
        app('config')->set('code-acl.models.user_has_permission.user_key.type', 'number');

        include_once __DIR__.'/../src/Database/Migrations/2014_10_12_111000_create_permissions_tables.php';

        (new \CreatePermissionsTables())->up();

        $permission = app(Permission::class)->create(['name' => 'test-permission']);

        $this->assertInstanceOf(Permission::class, $permission);

        (new \CreatePermissionsTables())->down();

        app('config')->set('code-acl.models.permission.primary_key.type', 'type');
        app('config')->set('code-acl.models.user_has_permission.permission_key.type', 'type');
        app('config')->set('code-acl.models.user_has_permission.user_key.type', 'type');

        (new \CreatePermissionsTables())->up();
        (new \CreatePermissionsTables())->down();
    }

    /** @test */
    public function it_test_field_option_for_roles_table()
    {
        app('config')->set('code-acl.models.role.table', 'roles_test');
        app('config')->set('code-acl.models.role.primary_key.type', 'number');

        app('config')->set('code-acl.models.user_has_role.table', 'roles_user_test');
        app('config')->set('code-acl.models.role_has_permission.table', 'permission_role_test');

        app('config')->set('code-acl.models.user_has_role.role_key.type', 'number');
        app('config')->set('code-acl.models.user_has_role.user_key.type', 'number');

        app('config')->set('code-acl.models.role_has_permission.permission_key.type', 'number');
        app('config')->set('code-acl.models.role_has_permission.role_key.type', 'number');

        include_once __DIR__.'/../src/Database/Migrations/2014_10_12_112000_create_roles_tables.php';

        (new \CreateRolesTables())->up();

        $role = app(Role::class)->create(['name' => 'test-role']);

        $this->assertInstanceOf(Role::class, $role);

        (new \CreateRolesTables())->down();

        app('config')->set('code-acl.models.role.primary_key.type', 'type');
        app('config')->set('code-acl.models.user_has_role.role_key.type', 'type');
        app('config')->set('code-acl.models.user_has_role.user_key.type', 'type');
        app('config')->set('code-acl.models.role_has_permission.permission_key.type', 'type');
        app('config')->set('code-acl.models.role_has_permission.role_key.type', 'type');

        (new \CreateRolesTables())->up();
        (new \CreateRolesTables())->down();
    }

    /** @test */
    public function it_test_field_option_for_modules_table()
    {
        app('config')->set('code-acl.models.module.table', 'modules_test');
        app('config')->set('code-acl.models.user_has_module.table', 'modules_user_test');
        app('config')->set('code-acl.models.module.primary_key.type', 'number');
        app('config')->set('code-acl.models.user_has_module.module_key.type', 'number');
        app('config')->set('code-acl.models.user_has_module.user_key.type', 'number');

        include_once __DIR__.'/../src/Database/Migrations/2014_10_12_113000_create_modules_tables.php';

        (new \CreateModulesTables())->up();

        $module = app(Module::class)->create(['name' => 'test-module']);

        $this->assertInstanceOf(Module::class, $module);

        (new \CreateModulesTables())->down();

        app('config')->set('code-acl.models.module.primary_key.type', 'type');
        app('config')->set('code-acl.models.user_has_module.module_key.type', 'type');
        app('config')->set('code-acl.models.user_has_module.user_key.type', 'type');

        (new \CreateModulesTables())->up();
        (new \CreateModulesTables())->down();
    }

    /** @test */
    public function it_test_field_option_for_systems_table()
    {
        app('config')->set('code-acl.models.system.table', 'systems_test');
        app('config')->set('code-acl.models.user_has_system.table', 'systems_user_test');
        app('config')->set('code-acl.models.system.primary_key.type', 'number');
        app('config')->set('code-acl.models.user_has_system.system_key.type', 'number');
        app('config')->set('code-acl.models.user_has_system.user_key.type', 'number');

        include_once __DIR__.'/../src/Database/Migrations/2014_10_12_114000_create_systems_tables.php';

        (new \CreateSystemsTables())->up();

        $system = app(System::class)->create(['name' => 'test-system']);

        $this->assertInstanceOf(System::class, $system);

        (new \CreateSystemsTables())->down();

        app('config')->set('code-acl.models.system.primary_key.type', 'type');
        app('config')->set('code-acl.models.user_has_system.system_key.type', 'type');
        app('config')->set('code-acl.models.user_has_system.user_key.type', 'type');

        (new \CreateSystemsTables())->up();
        (new \CreateSystemsTables())->down();
    }
}
