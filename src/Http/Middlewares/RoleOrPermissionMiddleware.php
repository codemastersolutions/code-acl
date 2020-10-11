<?php

namespace CodeMaster\CodeAcl\Http\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use CodeMaster\CodeAcl\Exceptions\UnauthorizedException;

class RoleOrPermissionMiddleware
{
    public function handle($request, Closure $next, $roleOrPermission, $guard = null)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (!Auth::user()->hasAnyRole($rolesOrPermissions) && !Auth::user()->checkPermissionsInRoles($rolesOrPermissions)) {
            throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
        }

        return $next($request);
    }
}
