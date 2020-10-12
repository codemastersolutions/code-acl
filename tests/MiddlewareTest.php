<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Http\Middlewares\PermissionMiddleware;
use CodeMaster\CodeAcl\Http\Middlewares\RoleMiddleware;
use CodeMaster\CodeAcl\Http\Middlewares\RoleOrPermissionMiddleware;
use CodeMaster\CodeAcl\Exceptions\UnauthorizedException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MiddlewareTest extends TestCase
{
    protected $roleMiddleware;
    protected $permissionMiddleware;
    protected $roleOrPermissionMiddleware;

    private const html = '<html></html>';

    public function setUp(): void
    {
        parent::setUp();

        $this->roleMiddleware = new RoleMiddleware();

        $this->permissionMiddleware = new PermissionMiddleware();

        $this->roleOrPermissionMiddleware = new RoleOrPermissionMiddleware();
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
    public function a_guest_cannot_access_a_route_protected_by_rolemiddleware()
    {
        $this->assertEquals(
            $this->runMiddleware(
                $this->roleMiddleware, 'Supervisor'
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
                return (new Response())->setContent(self::html);
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
                return (new Response())->setContent(self::html);
            }, 'some-permission');
        } catch (UnauthorizedException $e) {
            $requiredPermissions = $e->getRequiredPermissions();
        }

        $this->assertEquals(['some-permission'], $requiredPermissions);
    }

    protected function runMiddleware($middleware, $parameter, $guard = null)
    {
        try {
            return $middleware->handle(new Request(), function () {
                return (new Response())->setContent(self::html);
            }, $parameter, $guard)->status();
        } catch (UnauthorizedException $e) {
            return $e->getStatusCode();
        }
    }
}
