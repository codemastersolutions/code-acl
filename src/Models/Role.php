<?php

namespace CodeMaster\CodeAcl\Models;

use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Events\RoleDeleted;
use CodeMaster\CodeAcl\Events\RoleSaved;
use CodeMaster\CodeAcl\Events\RoleUpdated;
use CodeMaster\CodeAcl\Exceptions\RoleAlreadyExists;
use CodeMaster\CodeAcl\Exceptions\RoleDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\RoleException;
use CodeMaster\CodeAcl\Traits\HasPermissions;
use CodeMaster\CodeAcl\Traits\SetUpModel;
use CodeMaster\CodeAcl\Traits\Sluggable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Role extends Model implements RoleContract
{
    use SetUpModel, Sluggable, HasPermissions;

    protected $fillable = ['name'];

    protected $dispatchesEvents = [
        'saved' => RoleSaved::class,
        'updated' => RoleUpdated::class,
        'deleted' => RoleDeleted::class,
    ];

    /**
     * Model constructor
     *
     * @param array|null $attributes
     */
    public function __construct(array $attributes = [])
    {
        self::$modelData = config('code-acl.models.role');

        $this->setUp();

        parent::__construct($attributes);
    }

    /**
     * @inheritDoc
     */
    public static function create(array $attributes): self
    {
        $role = self::whereName($attributes['name'])->first();

        if ($role) {
            throw RoleAlreadyExists::create($attributes['name']);
        }

        try {
            $role = tap(static::query()->newModelInstance($attributes), function ($instance) {
                $instance->save();
            });
        } catch (\Exception $exception) {
            throw RoleException::create($attributes['name'], $exception);
        }

        return $role;
    }

    /**
     * @inheritDoc
     */
    public static function findById($id): self
    {
        $role = self::find($id);

        if (! $role) {
            throw RoleDoesNotExist::withId($id);
        }

        return $role;
    }

    /**
     * @inheritDoc
     */
    public static function findByName(string $name): self
    {
        $role = self::whereName($name)->first();

        if (! $role) {
            throw RoleDoesNotExist::withName($name);
        }

        return $role;
    }

    /**
     * @inheritDoc
     */
    public static function findBySlug(string $slug): self
    {
        $role = self::whereSlug($slug)->first();

        if (! $role) {
            throw RoleDoesNotExist::withSlug($slug);
        }

        return $role;
    }

    /**
     * @inheritDoc
     */
    public static function findOrCreate(string $name): self
    {
        $role = self::whereName($name)->first();

        if (! $role) {
            return self::create(['name'=> $name]);
        }

        return $role;
    }

    /**
     * @inheritDoc
     */
    public static function getNames(): Collection
    {
        return self::all()->pluck('name');
    }
}
