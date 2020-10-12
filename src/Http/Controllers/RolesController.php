<?php

namespace CodeMaster\CodeAcl\Http\Controllers;

use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use CodeMaster\CodeAcl\Http\Requests\RolesRequest;
use CodeMaster\CodeAcl\Http\Requests\RolePermissionsRequest;
use CodeMaster\CodeAcl\Http\Resources\PermissionsResource;
use CodeMaster\CodeAcl\Http\Resources\RolesResource;
use Illuminate\Http\Request;

class RolesController extends BaseController
{
    /** @var \CodeMaster\CodeAcl\Contracts\Role $model */
    private static $model;

    /** @var array|null $modelMetaData */
    private static $modelMetaData;

    /** @var array|null $modelMetaData */
    private static $orderBy;

    /** @var int $items */
    private static int $perPage;

    public function __construct()
    {
        self::$model = app(config('code-acl.models.role.class'));
        self::$modelMetaData = config('code-acl.models.role.meta_data');

        if (empty(self::$modelMetaData)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$orderBy = self::$modelMetaData['order_by'];

        $items = self::$modelMetaData['pagination']['per_page'];

        self::$perPage = is_int($items) ? ((int) $items > 0 ? (int) $items : 0 ): 0;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $roles = self::$model;

        if (self::$orderBy && count(self::$orderBy) > 0) {
            $roles = $roles::orderBy(self::$orderBy['field'], self::$orderBy['direction']);
        }

        $roles = (self::$perPage > 0) ? $roles->paginate(self::$perPage) : $roles->get();

        return RolesResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \CodeMaster\CodeAcl\Http\Requests\RolesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolesRequest $request)
    {
        $role = self::$model::create($request->validated());
        $role->refresh();
        return response()->json(RolesResource::make($role), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role $role
     * @return CodeMaster\CodeAcl\Http\Resources\RolesResource
     */
    public function show(RoleContract $role)
    {
        return RolesResource::make($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \CodeMaster\CodeAcl\Contracts\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoleContract $role)
    {
        $role->fill($request->all());
        $role->save();

        return response()->json(RolesResource::make($role), 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoleContract $role)
    {
        $role->delete();
        return response()->noContent(204);
    }

    public function givePermissions(RoleContract $role, RolePermissionsRequest $request)
    {
        try {
            $data = $request->validated();
            $role->givePermissions($data['permissions']);
            return response()->json(['result' => true], 201);
        } catch(\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()], 422);
        }
    }

    public function revokePermissions(RoleContract $role, RolePermissionsRequest $request)
    {
        try {
            $data = $request->validated();
            $role->revokePermissions($data['permissions']);
            return response()->noContent();
        } catch(\Exception $e) {
            return response()->json(['result' => false, 'error' => $e->getMessage()], 422);
        }
    }

    public function permissions(RoleContract $role, Request $request)
    {
        $permissions = $role->permissions->all();

        return response()->json(PermissionsResource::collection($permissions));
    }
}
