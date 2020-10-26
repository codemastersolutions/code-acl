<?php

namespace CodeMaster\CodeAcl\Http\Controllers;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use CodeMaster\CodeAcl\Http\Requests\SystemsRequest;
use CodeMaster\CodeAcl\Http\Resources\SystemsResource;
use Illuminate\Http\Request;

class SystemsController extends BaseController
{
    /** @var \CodeMaster\CodeAcl\Contracts\System $model */
    private static $model;

    /** @var array|null $modelMetaData */
    private static $modelMetaData;

    /** @var array|null $modelMetaData */
    private static $orderBy;

    /** @var int $items */
    private static int $perPage;

    public function __construct()
    {
        self::$model = app(config('code-acl.models.system.class'));
        self::$modelMetaData = config('code-acl.models.system.meta_data');

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
        $systems = self::$model;

        if (self::$orderBy && count(self::$orderBy) > 0) {
            $systems = $systems::orderBy(self::$orderBy['field'], self::$orderBy['direction']);
        }

        $systems = (self::$perPage > 0) ? $systems->paginate(self::$perPage) : $systems->get();

        return SystemsResource::collection($systems);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \CodeMaster\CodeAcl\Http\Requests\SystemsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SystemsRequest $request)
    {
        $system = self::$model::create($request->validated());
        $system->refresh();
        return response()->json(SystemsResource::make($system), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role $system
     * @return CodeMaster\CodeAcl\Http\Resources\SystemsResource
     */
    public function show(SystemContract $system)
    {
        return SystemsResource::make($system);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \CodeMaster\CodeAcl\Contracts\Role $system
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemContract $system)
    {
        $system->fill($request->all());
        $system->save();

        return response()->json(SystemsResource::make($system), 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role $system
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemContract $system)
    {
        $system->delete();
        return response()->noContent(204);
    }
}
