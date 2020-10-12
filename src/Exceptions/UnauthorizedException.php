<?php

namespace CodeMaster\CodeAcl\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UnauthorizedException extends HttpException
{
    private $requiredRoles = [];

    private $requiredPermissions = [];

    private const DISPLAY_EXCEPTION = 'code-acl.display_permission_in_exception';

    public static function forRoles(array $roles): self
    {
        $message = 'User does not have the right roles.';

        if (config(self::DISPLAY_EXCEPTION)) {
            $permStr = implode(', ', $roles);
            $message = 'User does not have the right roles. Necessary roles are '.$permStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredRoles = $roles;

        return $exception;
    }

    public static function forPermissions(array $permissions): self
    {
        $message = 'User does not have the right permissions.';

        if (config(self::DISPLAY_EXCEPTION)) {
            $permStr = implode(', ', $permissions);
            $message = 'User does not have the right permissions. Necessary permissions are '.$permStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredPermissions = $permissions;

        return $exception;
    }

    public static function forSystems(array $systems): self
    {
        $message = 'User does not have the right systems.';

        if (config('code-acl.display_system_in_exception')) {
            $systemStr = implode(', ', $systems);
            $message = 'User does not have the right systems. Necessary systems are '.$systemStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredSystems = $systems;

        return $exception;
    }

    public static function forModules(array $modules): self
    {
        $message = 'User does not have the right modules.';

        if (config('code-acl.display_module_in_exception')) {
            $moduleStr = implode(', ', $modules);
            $message = 'User does not have the right modules. Necessary modules are '.$moduleStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredModules = $modules;

        return $exception;
    }

    public static function forRolesOrPermissions(array $rolesOrPermissions): self
    {
        $message = 'User does not have any of the necessary access rights.';

        if (config(self::DISPLAY_EXCEPTION) && config('code-acl.display_role_in_exception')) {
            $permStr = implode(', ', $rolesOrPermissions);
            $message = 'User does not have the right permissions. Necessary permissions are '.$permStr;
        }

        $exception = new static(403, $message, null, []);
        $exception->requiredPermissions = $rolesOrPermissions;

        return $exception;
    }

    public static function notLoggedIn(): self
    {
        return new static(403, 'User is not logged in.', null, []);
    }

    public function getRequiredRoles(): array
    {
        return $this->requiredRoles;
    }

    public function getRequiredPermissions(): array
    {
        return $this->requiredPermissions;
    }
}
