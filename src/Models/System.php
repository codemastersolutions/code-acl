<?php

namespace CodeMaster\CodeAcl\Models;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use CodeMaster\CodeAcl\Events\System\SystemDeleted;
use CodeMaster\CodeAcl\Events\System\SystemSaved;
use CodeMaster\CodeAcl\Events\System\SystemUpdated;
use CodeMaster\CodeAcl\Exceptions\SystemAlreadyExists;
use CodeMaster\CodeAcl\Exceptions\SystemDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\SystemException;
use CodeMaster\CodeAcl\Traits\HasPermissions;
use CodeMaster\CodeAcl\Traits\SetUpModel;
use CodeMaster\CodeAcl\Traits\Sluggable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class System extends Model implements SystemContract
{
    use SetUpModel, Sluggable, HasPermissions;

    protected $fillable = ['name'];

    protected $dispatchesEvents = [
        'saved' => SystemSaved::class,
        'updated' => SystemUpdated::class,
        'deleted' => SystemDeleted::class,
    ];

    /**
     * Model constructor
     *
     * @param array|null $attributes
     */
    public function __construct(array $attributes = [])
    {
        self::$modelData = config('code-acl.models.system');

        $this->setUp();

        parent::__construct($attributes);
    }

    /**
     * @inheritDoc
     */
    public static function create(array $attributes): self
    {
        $system = self::whereName($attributes['name'])->first();

        if ($system) {
            throw SystemAlreadyExists::create($attributes['name']);
        }

        try {
            $system = tap(static::query()->newModelInstance($attributes), function ($instance) {
                $instance->save();
            });
        } catch (\Exception $exception) {
            throw SystemException::create($attributes['name'], $exception);
        }

        return $system;
    }

    /**
     * @inheritDoc
     */
    public static function findById($id): self
    {
        $system = self::find($id);

        if (! $system) {
            throw SystemDoesNotExist::withId($id);
        }

        return $system;
    }

    /**
     * @inheritDoc
     */
    public static function findByName(string $name): self
    {
        $system = self::whereName($name)->first();

        if (! $system) {
            throw SystemDoesNotExist::withName($name);
        }

        return $system;
    }

    /**
     * @inheritDoc
     */
    public static function findBySlug(string $slug): self
    {
        $system = self::whereSlug($slug)->first();

        if (! $system) {
            throw SystemDoesNotExist::withSlug($slug);
        }

        return $system;
    }

    /**
     * @inheritDoc
     */
    public static function findOrCreate(string $name): self
    {
        $system = self::whereName($name)->first();

        if (! $system) {
            return self::create(['name'=> $name]);
        }

        return $system;
    }

    /**
     * @inheritDoc
     */
    public static function getNames(): Collection
    {
        return self::all()->pluck('name');
    }
}
