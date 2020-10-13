<?php

namespace CodeMaster\CodeAcl\Models;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use CodeMaster\CodeAcl\Events\Module\ModuleDeleted;
use CodeMaster\CodeAcl\Events\Module\ModuleSaved;
use CodeMaster\CodeAcl\Events\Module\ModuleUpdated;
use CodeMaster\CodeAcl\Exceptions\ModuleAlreadyExists;
use CodeMaster\CodeAcl\Exceptions\ModuleDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\ModuleException;
use CodeMaster\CodeAcl\Traits\HasPermissions;
use CodeMaster\CodeAcl\Traits\SetUpModel;
use CodeMaster\CodeAcl\Traits\Sluggable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Module extends Model implements ModuleContract
{
    use SetUpModel, Sluggable, HasPermissions;

    protected $fillable = ['name'];

    protected $dispatchesEvents = [
        'saved' => ModuleSaved::class,
        'updated' => ModuleUpdated::class,
        'deleted' => ModuleDeleted::class,
    ];

    /**
     * Model constructor
     *
     * @param array|null $attributes
     */
    public function __construct(array $attributes = [])
    {
        self::$modelData = config('code-acl.models.module');

        $this->setUp();

        parent::__construct($attributes);
    }

    /**
     * @inheritDoc
     */
    public static function create(array $attributes): self
    {
        $module = self::whereName($attributes['name'])->first();

        if ($module) {
            throw ModuleAlreadyExists::create($attributes['name']);
        }

        try {
            $module = tap(static::query()->newModelInstance($attributes), function ($instance) {
                $instance->save();
            });
        } catch (\Exception $exception) {
            throw ModuleException::create($attributes['name'], $exception);
        }

        return $module;
    }

    /**
     * @inheritDoc
     */
    public static function findById($id): self
    {
        $module = self::find($id);

        if (! $module) {
            throw ModuleDoesNotExist::withId($id);
        }

        return $module;
    }

    /**
     * @inheritDoc
     */
    public static function findByName(string $name): self
    {
        $module = self::whereName($name)->first();

        if (! $module) {
            throw ModuleDoesNotExist::withName($name);
        }

        return $module;
    }

    /**
     * @inheritDoc
     */
    public static function findBySlug(string $slug): self
    {
        $module = self::whereSlug($slug)->first();

        if (! $module) {
            throw ModuleDoesNotExist::withSlug($slug);
        }

        return $module;
    }

    /**
     * @inheritDoc
     */
    public static function findOrCreate(string $name): self
    {
        $module = self::whereName($name)->first();

        if (! $module) {
            return self::create(['name'=> $name]);
        }

        return $module;
    }

    /**
     * @inheritDoc
     */
    public static function getNames(): Collection
    {
        return self::all()->pluck('name');
    }
}
