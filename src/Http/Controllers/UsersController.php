<?php

namespace CodeMaster\CodeAcl\Http\Controllers;

use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use CodeMaster\CodeAcl\Exceptions\UserModelNotFound;
use CodeMaster\CodeAcl\Http\Requests\UserModulesRequest;
use CodeMaster\CodeAcl\Http\Requests\UserPermissionsRequest;
use CodeMaster\CodeAcl\Http\Requests\UserRolesRequest;
use CodeMaster\CodeAcl\Http\Requests\UserSystemsRequest;
use CodeMaster\CodeAcl\Http\Resources\ModulesResource;
use CodeMaster\CodeAcl\Http\Resources\PermissionsResource;
use CodeMaster\CodeAcl\Http\Resources\RolesResource;
use CodeMaster\CodeAcl\Http\Resources\SystemsResource;

class UsersController extends BaseController
{
    /** @var \Illuminate\Database\Eloquent\Model $model */
    private static $model;

    /** @var array|null $permissionUserMetaData */
    private static $moduleUserMetaData;

    /** @var array|null $permissionUserMetaData */
    private static $permissionUserMetaData;

    /** @var array|null $roleUserMetaData */
    private static $roleUserMetaData;

    /** @var array|null $systemUserMetaData */
    private static $systemUserMetaData;

    /** @var array|null $orderByRole */
    private static $orderByRole;

    /** @var array|null $orderByPermission */
    private static $orderByPermission;

    public const URL_MODULES = 'users/{user}/modules';
    public const URL_PERMISSIONS = 'users/{user}/permissions';
    public const URL_ROLES = 'users/{user}/roles';
    public const URL_SYSTEMS = 'users/{user}/systems';

    public function __construct()
    {
        $modelClass = config('code-acl.defaults.user');

        if (empty($modelClass)) {
            throw UserModelNotFound::config('config/code-acl.php');
        }

        self::$model = app($modelClass);
        self::$moduleUserMetaData = config('code-acl.models.user_has_module.meta_data');
        self::$permissionUserMetaData = config('code-acl.models.user_has_permission.meta_data');
        self::$roleUserMetaData = config('code-acl.models.user_has_role.meta_data');
        self::$systemUserMetaData = config('code-acl.models.user_has_system.meta_data');

        if (empty(self::$permissionUserMetaData) || empty(self::$roleUserMetaData) || empty(self::$moduleUserMetaData) || empty(self::$systemUserMetaData)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$orderByPermission = self::$permissionUserMetaData['order_by'];
        self::$orderByRole = self::$roleUserMetaData['order_by'];
    }

    /**
     * Give modules to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\User $user
     * @param \CodeMaster\CodeAcl\Http\Requests\UserModulesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function giveModules(UserContract $user, UserModulesRequest $request)
    {
        try {
            $data = $request->validated();
            $user->giveModules($data['modules']);
            return response()->json(['result' => true], 201);
        } catch(\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Give permissions to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\User $user
     * @param \CodeMaster\CodeAcl\Http\Requests\UserPermissionsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function givePermissions(UserContract $user, UserPermissionsRequest $request)
    {
        try {
            $data = $request->validated();
            $user->givePermissions($data['permissions']);
            return response()->json(['result' => true], 201);
        } catch(\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Give roles to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role $user
     * @param \CodeMaster\CodeAcl\Http\Requests\UserRolesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function giveRoles(UserContract $user, UserRolesRequest $request)
    {
        try {
            $data = $request->validated();
            $user->giveRoles($data['roles']);
            return response()->json(['result' => true], 201);
        } catch(\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Give systems to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\System $user
     * @param \CodeMaster\CodeAcl\Http\Requests\UserSystemsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function giveSystems(UserContract $user, UserSystemsRequest $request)
    {
        try {
            $data = $request->validated();
            $user->giveSystems($data['systems']);
            return response()->json(['result' => true], 201);
        } catch(\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Get modules assigned to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\User $user
     * @return \Illuminate\Http\Response
     */
    public function modules(UserContract $user)
    {
        $perPage = per_page(self::$moduleUserMetaData);

        $modules = $user->modules();

        if (self::$orderByPermission && count(self::$orderByPermission) > 0) {
            $modules = $modules->orderBy(
                self::$orderByPermission['field'],
                self::$orderByPermission['direction']
            );
        }

        $modules = ($perPage > 0) ? $modules->paginate($perPage) : $modules->get();

        return response()->json(ModulesResource::collection($modules));
    }

    /**
     * Get permissions assigned to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\User $user
     * @return \Illuminate\Http\Response
     */
    public function permissions(UserContract $user)
    {
        $perPage = per_page(self::$permissionUserMetaData);

        $permissions = $user->permissions();

        if (self::$orderByPermission && count(self::$orderByPermission) > 0) {
            $permissions = $permissions->orderBy(
                self::$orderByPermission['field'],
                self::$orderByPermission['direction']
            );
        }

        $permissions = ($perPage > 0) ? $permissions->paginate($perPage) : $permissions->get();

        return response()->json(PermissionsResource::collection($permissions));
    }

    /**
     * Revoke modules assigned to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\User $user
     * @param \CodeMaster\CodeAcl\Http\Requests\UserModulesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function revokeModules(UserContract $user, UserModulesRequest $request)
    {
        try {
            $data = $request->validated();
            $user->revokeModules($data['modules']);
            return response()->noContent();
        } catch(\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Revoke permissions assigned to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\User $user
     * @param \CodeMaster\CodeAcl\Http\Requests\UserPermissionsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function revokePermissions(UserContract $user, UserPermissionsRequest $request)
    {
        try {
            $data = $request->validated();
            $user->revokePermissions($data['permissions']);
            return response()->noContent();
        } catch(\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Revoke roles assigned to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\User $user
     * @param \CodeMaster\CodeAcl\Http\Requests\UserRolesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function revokeRoles(UserContract $user, UserRolesRequest $request)
    {
        try {
            $data = $request->validated();
            $user->revokeRoles($data['roles']);
            return response()->noContent();
        } catch(\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Revoke systems assigned to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\User $user
     * @param \CodeMaster\CodeAcl\Http\Requests\UserRolesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function revokeSystems(UserContract $user, UserSystemsRequest $request)
    {
        try {
            $data = $request->validated();
            $user->revokeSystems($data['systems']);
            return response()->noContent();
        } catch(\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Get roles assigned to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role $user
     * @return \Illuminate\Http\Response
     */
    public function roles(UserContract $user)
    {
        $perPage = per_page(self::$roleUserMetaData);

        $roles = $user->roles();

        if (self::$orderByRole && count(self::$orderByRole) > 0) {
            $roles = $roles->orderBy(
                self::$orderByRole['field'],
                self::$orderByRole['direction']
            );
        }

        $roles = ($perPage > 0) ? $roles->paginate($perPage) : $roles->get();

        return response()->json(RolesResource::collection($roles));
    }

    /**
     * Get systems assigned to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\User $user
     * @return \Illuminate\Http\Response
     */
    public function systems(UserContract $user)
    {
        $perPage = per_page(self::$roleUserMetaData);

        $systems = $user->systems();

        if (self::$orderByRole && count(self::$orderByRole) > 0) {
            $systems = $systems->orderBy(
                self::$orderByRole['field'],
                self::$orderByRole['direction']
            );
        }

        $systems = ($perPage > 0) ? $systems->paginate($perPage) : $systems->get();

        return response()->json(SystemsResource::collection($systems));
    }
}
