<?php

namespace CodeMaster\CodeAcl\Http\Controllers;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use CodeMaster\CodeAcl\Http\Requests\PermissionsRequest;
use CodeMaster\CodeAcl\Http\Resources\PermissionsResource;
use Illuminate\Http\Request;

class PermissionsController extends BaseController
{
    /** @var \CodeMaster\CodeAcl\Contracts\Permission $model */
    private static $model;

    /** @var array|null $modelMetaData */
    private static $modelMetaData;

    /** @var array|null $orderBy */
    private static $orderBy;

    /** @var int $items */
    private static int $perPage;

    public function __construct()
    {
        self::$model = app(config('code-acl.models.permission.class'));
        self::$modelMetaData = config('code-acl.models.permission.meta_data');

        if (empty(self::$modelMetaData)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$orderBy = self::$modelMetaData['order_by'];
        self::$perPage = per_page(self::$modelMetaData);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $permissions = self::$model;

        if (self::$orderBy && count(self::$orderBy) > 0) {
            $permissions = $permissions::orderBy(self::$orderBy['field'], self::$orderBy['direction']);
        }

        $permissions = (self::$perPage > 0) ? $permissions->paginate(self::$perPage) : $permissions->get();

        return PermissionsResource::collection($permissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \CodeMaster\CodeAcl\Http\Requests\PermissionsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionsRequest $request)
    {
        $permission = self::$model::create($request->validated());
        $permission->refresh();
        return response()->json(PermissionsResource::make($permission), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Permission $permission
     * @return CodeMaster\CodeAcl\Http\Resources\PermissionsResource
     */
    public function show(PermissionContract $permission)
    {
        return PermissionsResource::make($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \CodeMaster\CodeAcl\Contracts\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PermissionContract $permission)
    {
        $permission->fill($request->all());
        $permission->save();

        return response()->json(PermissionsResource::make($permission), 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermissionContract $permission)
    {
        $permission->delete();
        return response()->noContent(204);
    }
}
