<?php

namespace CodeMaster\CodeAcl\Http\Controllers;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use CodeMaster\CodeAcl\Http\Requests\ModulesRequest;
use CodeMaster\CodeAcl\Http\Resources\ModulesResource;
use Illuminate\Http\Request;

class ModulesController extends BaseController
{
    /** @var \CodeMaster\CodeAcl\Contracts\Module $model */
    private static $model;

    /** @var array|null $modelMetaData */
    private static $modelMetaData;

    /** @var array|null $modelMetaData */
    private static $orderBy;

    /** @var int $items */
    private static int $perPage;

    public function __construct()
    {
        self::$model = app(config('code-acl.models.module.class'));
        self::$modelMetaData = config('code-acl.models.module.meta_data');

        if (empty(self::$modelMetaData)) {
            throw ConfigNotLoaded::config('config/code-acl.php');
        }

        self::$orderBy = self::$modelMetaData['order_by'];

        $items = self::$modelMetaData['pagination']['per_page'];

        if (!is_int($items)) {
            self::$perPage = 0;
        }

        self::$perPage = (int) $items > 0 ? (int) $items : 0;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $modules = self::$model;

        if (self::$orderBy && count(self::$orderBy) > 0) {
            $modules = $modules::orderBy(self::$orderBy['field'], self::$orderBy['direction']);
        }

        $modules = (self::$perPage > 0) ? $modules->paginate(self::$perPage) : $modules->get();

        return ModulesResource::collection($modules);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \CodeMaster\CodeAcl\Http\Requests\ModulesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModulesRequest $request)
    {
        $module = self::$model::create($request->validated());
        $module->refresh();
        return response()->json(ModulesResource::make($module), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Module $module
     * @return CodeMaster\CodeAcl\Http\Resources\ModulesResource
     */
    public function show(ModuleContract $module)
    {
        return ModulesResource::make($module);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \CodeMaster\CodeAcl\Contracts\Module $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModuleContract $module)
    {
        $module->fill($request->all());
        $module->save();

        return response()->json(ModulesResource::make($module), 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Module $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModuleContract $module)
    {
        $module->delete();
        return response()->noContent(204);
    }
}
