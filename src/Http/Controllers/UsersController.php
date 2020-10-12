<?php

namespace CodeMaster\CodeAcl\Http\Controllers;

use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use CodeMaster\CodeAcl\Exceptions\UserModelNotFound;
use CodeMaster\CodeAcl\Http\Requests\UserPermissionsRequest;
use CodeMaster\CodeAcl\Http\Requests\UserRolesRequest;
use CodeMaster\CodeAcl\Http\Resources\PermissionsResource;
use CodeMaster\CodeAcl\Http\Resources\RolesResource;
use Illuminate\Http\Request;

class UsersController extends BaseController
{
    /** @var \Illuminate\Database\Eloquent\Model $model */
    private static $model;

    /** @var array|null $permissionUserMetaData */
    private static $permissionUserMetaData;

    /** @var array|null $roleUserMetaData */
    private static $roleUserMetaData;

    /** @var array|null $orderByRole */
    private static $orderByRole;

    /** @var array|null $orderByPermission */
    private static $orderByPermission;

    public function __construct()
    {
        self::$model = app(config('code-acl.defaults.user'));
        self::$permissionUserMetaData = config('code-acl.models.user_has_permission.meta_data');
        self::$roleUserMetaData = config('code-acl.models.user_has_role.meta_data');

        if (empty(self::$model)) {
            throw UserModelNotFound::config('config/code-acl.php');
        }

        if (empty(self::$permissionUserMetaData) || empty(self::$roleUserMetaData)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$orderByPermission = self::$permissionUserMetaData['order_by'];
        self::$orderByRole = self::$roleUserMetaData['order_by'];
    }

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

    public function permissions(UserContract $user, Request $request)
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

    public function roles(UserContract $user, Request $request)
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
}
