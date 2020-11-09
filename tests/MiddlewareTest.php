<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Http\Middlewares\ModuleMiddleware;
use CodeMaster\CodeAcl\Http\Middlewares\PermissionMiddleware;
use CodeMaster\CodeAcl\Http\Middlewares\RoleMiddleware;
use CodeMaster\CodeAcl\Http\Middlewares\RoleOrPermissionMiddleware;
use CodeMaster\CodeAcl\Http\Middlewares\SystemMiddleware;
use CodeMaster\CodeAcl\Exceptions\UnauthorizedException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MiddlewareTest extends TestCase
{
    protected $moduleMiddleware;
    protected $permissionMiddleware;
    protected $roleMiddleware;
    protected $roleOrPermissionMiddleware;
    protected $systemMiddleware;

    private const HTML = '<html></html>';

    public function setUp(): void
    {
        parent::setUp();

        $this->moduleMiddleware = new ModuleMiddleware();
        $this->permissionMiddleware = new PermissionMiddleware();
        $this->roleMiddleware = new RoleMiddleware();
        $this->roleOrPermissionMiddleware = new RoleOrPermissionMiddleware();
        $this->systemMiddleware = new SystemMiddleware();
    }

    /** @test */
    public function a_guest_cannot_access_a_route_protected_by_the_role_or_permission_middleware()
    {
        $this->assertEquals(
            $this->runMiddleware(
                $this->roleOrPermissionMiddleware, 'Supervisor'
            ), 403);
    }

    /** @test */
    public function a_guest_cannot_access_a_route_protected_by_role_middleware()
    {
        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware, 'Supervisor'
            ), 403);
    }

    /** @test */
    public function a_guest_cannot_access_a_route_protected_by_system_middleware()
    {
        $this->assertEquals(
            $this->runMiddleware(
                $this->systemMiddleware, 'System'
            ), 403);
    }

    /** @test */
    public function a_guest_cannot_access_a_route_protected_by_module_middleware()
    {
        $this->assertEquals(
            $this->runMiddleware(
                $this->moduleMiddleware, 'Module'
            ), 403);
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_role_middleware_if_have_this_role()
    {
        Auth::login($this->testUser);

        $this->testUser->assignRole('Supervisor');

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware, 'Supervisor'
            ), 200);
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_system_middleware_if_have_this_role()
    {
        Auth::login($this->testUser);

        $this->testUser->assignSystem('New System');

        $this->assertEquals(
            $this->runMiddleware(
                $this->systemMiddleware, 'New System'
            ), 200);
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_module_middleware_if_have_this_role()
    {
        Auth::login($this->testUser);

        $this->testUser->assignModule('New Module');

        $this->assertEquals(
            $this->runMiddleware(
                $this->moduleMiddleware, 'New Module'
            ), 200);
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_system_middleware_if_have_this_system_by_array()
    {
        Auth::login($this->testUser);

        $this->testUser->assignSystem('New System');

        $this->assertEquals(
            $this->runMiddleware(
                $this->systemMiddleware, ['New System']
            ), 200);
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_module_middleware_if_have_this_module_by_array()
    {
        Auth::login($this->testUser);

        $this->testUser->assignModule('New Module');

        $this->assertEquals(
            $this->runMiddleware(
                $this->moduleMiddleware, ['New Module']
            ), 200);
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_permission_middleware_if_have_this_permission_by_array()
    {
        Auth::login($this->testUser);

        $this->testUser->givePermissions('Insert Articles');

        $this->assertEquals(
            $this->runMiddleware(
                $this->permissionMiddleware, ['Insert Articles']
            ), 200);
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_this_role_middleware_if_have_one_of_the_roles()
    {
        Auth::login($this->testUser);

        $this->testUser->assignRole('Supervisor');

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware, 'Supervisor|Publisher'
            ), 200);

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware, ['Publisher', 'Supervisor']
            ), 200);
    }

    /** @test */
    public function a_user_cannot_access_a_route_protected_by_the_role_middleware_if_have_a_different_role()
    {
        Auth::login($this->testUser);

        $this->testUser->assignRole(['Supervisor']);

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware, 'Publisher'
            ), 403);
    }

    /** @test */
    public function a_user_cannot_access_a_route_protected_by_role_middleware_if_have_not_roles()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware, 'Supervisor|Publisher'
            ), 403);
    }

    /** @test */
    public function a_user_cannot_access_a_route_protected_by_role_middleware_if_role_is_undefined()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware, ''
            ), 403);
    }

    /** @test */
    public function a_guest_cannot_access_a_route_protected_by_the_permission_middleware()
    {
        $this->assertEquals(
            $this->runMiddleware(
                $this->permissionMiddleware, 'edit-articles'
            ), 403);
    }

    /** @test */
    public function a_user_cannot_access_a_route_protected_by_permission_middleware_if_have_not_permissions()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            $this->runMiddleware(
                $this->permissionMiddleware, 'update-news|delete-news'
            ), 403);
    }

    /** @test */
    public function a_user_can_access_a_route_protected_by_permission_or_role_middleware_if_has_this_permission_or_role()
    {
        Auth::login($this->testUser);

        $this->testUser->assignRole('Supervisor');

        $this->assertEquals(
            $this->runMiddleware($this->roleOrPermissionMiddleware, 'Supervisor|edit-news|edit-articles'),
            200
        );

        $this->assertEquals(
            $this->runMiddleware($this->roleOrPermissionMiddleware, 'edit-articles'),
            200
        );

        $this->assertEquals(
            $this->runMiddleware($this->roleOrPermissionMiddleware, ['Supervisor', 'edit-articles']),
            200
        );

        $this->testUser->revokeRoles('Supervisor');

        $this->assertEquals(
            $this->runMiddleware($this->roleOrPermissionMiddleware, 'Supervisor|edit-articles'),
            403
        );

    }

    /** @test */
    public function a_user_can_not_access_a_route_protected_by_permission_or_role_middleware_if_have_not_this_permission_and_role()
    {
        Auth::login($this->testUser);

        $this->assertEquals(
            $this->runMiddleware($this->roleOrPermissionMiddleware, 'Supervisor|edit-articles'),
            403
        );

        $this->assertEquals(
            $this->runMiddleware($this->roleOrPermissionMiddleware, 'missingRole|missingPermission'),
            403
        );
    }

    /** @test */
    public function the_required_roles_can_be_fetched_from_the_exception()
    {
        Auth::login($this->testUser);

        $requiredRoles = [];

        try {
            $this->roleMiddleware->handle(new Request(), function () {
                return (new Response())->setContent(self::HTML);
            }, 'some-role');
        } catch (UnauthorizedException $e) {
            $requiredRoles = $e->getRequiredRoles();
        }

        $this->assertEquals(['some-role'], $requiredRoles);
    }

    /** @test */
    public function the_required_roles_can_be_showing_in_the_exception()
    {
        app('config')->set('code-acl.display_role_in_exception', true);

        Auth::login($this->testUser);

        $requiredRoles = [];

        try {
            $this->roleMiddleware->handle(new Request(), function () {
                return (new Response())->setContent(self::HTML);
            }, 'some-role');
        } catch (UnauthorizedException $e) {
            $requiredRoles = $e->getRequiredRoles();
        }

        $this->assertEquals(['some-role'], $requiredRoles);
    }

    /** @test */
    public function the_required_permissions_can_be_fetched_from_the_exception()
    {
        Auth::login($this->testUser);

        $requiredPermissions = [];

        try {
            $this->permissionMiddleware->handle(new Request(), function () {
                return (new Response())->setContent(self::HTML);
            }, 'some-permission');
        } catch (UnauthorizedException $e) {
            $requiredPermissions = $e->getRequiredPermissions();
        }

        $this->assertEquals(['some-permission'], $requiredPermissions);
    }

    /** @test */
    public function the_required_permissions_can_be_showing_in_the_exception()
    {
        app('config')->set('code-acl.display_permission_in_exception', true);

        Auth::login($this->testUser);

        $requiredPermissions = [];

        try {
            $this->permissionMiddleware->handle(new Request(), function () {
                return (new Response())->setContent(self::HTML);
            }, 'some-permission');
        } catch (UnauthorizedException $e) {
            $requiredPermissions = $e->getRequiredPermissions();
        }

        $this->assertEquals(['some-permission'], $requiredPermissions);
    }

    /** @test */
    public function the_required_modules_can_be_fetched_from_the_exception()
    {
        Auth::login($this->testUser);

        $requiredModules = [];

        try {
            $this->moduleMiddleware->handle(new Request(), function () {
                return (new Response())->setContent(self::HTML);
            }, 'some-module');
        } catch (UnauthorizedException $e) {
            $requiredModules = $e->getRequiredModules();
        }

        $this->assertEquals(['some-module'], $requiredModules);
    }

    /** @test */
    public function the_required_systems_can_be_showing_in_the_exception()
    {
        app('config')->set('code-acl.display_system_in_exception', true);

        Auth::login($this->testUser);

        $requiredSystems = [];

        try {
            $this->systemMiddleware->handle(new Request(), function () {
                return (new Response())->setContent(self::HTML);
            }, 'some-permission');
        } catch (UnauthorizedException $e) {
            $requiredSystems = $e->getRequiredSystems();
        }

        $this->assertEquals(['some-permission'], $requiredSystems);
    }

    /** @test */
    public function the_required_modules_can_be_showing_in_the_exception()
    {
        app('config')->set('code-acl.display_module_in_exception', true);

        Auth::login($this->testUser);

        $requiredModules = [];

        try {
            $this->moduleMiddleware->handle(new Request(), function () {
                return (new Response())->setContent(self::HTML);
            }, 'some-module');
        } catch (UnauthorizedException $e) {
            $requiredModules = $e->getRequiredModules();
        }

        $this->assertEquals(['some-module'], $requiredModules);
    }

    /** @test */
    public function the_required_roles_or_permissions_can_be_showing_in_the_exception()
    {
        app('config')->set('code-acl.display_permission_in_exception', true);
        app('config')->set('code-acl.display_role_in_exception', true);

        Auth::login($this->testUser);

        $requiredPermissions = [];

        try {
            $this->roleOrPermissionMiddleware->handle(new Request(), function () {
                return (new Response())->setContent(self::HTML);
            }, 'some-permission-or-role');
        } catch (UnauthorizedException $e) {
            $requiredPermissions = $e->getRequiredPermissions();
        }

        $this->assertEquals(['some-permission-or-role'], $requiredPermissions);
    }

    protected function runMiddleware($middleware, $parameter, $guard = null)
    {
        try {
            return $middleware->handle(new Request(), function () {
                return (new Response())->setContent(self::HTML);
            }, $parameter, $guard)->status();
        } catch (UnauthorizedException $e) {
            return $e->getStatusCode();
        }
    }
}
