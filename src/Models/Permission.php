<?php

namespace CodeMaster\CodeAcl\Models;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Events\Permission\PermissionCreated;
use CodeMaster\CodeAcl\Events\Permission\PermissionDeleted;
use CodeMaster\CodeAcl\Events\Permission\PermissionRetrieved;
use CodeMaster\CodeAcl\Events\Permission\PermissionSaved;
use CodeMaster\CodeAcl\Events\Permission\PermissionUpdated;
use CodeMaster\CodeAcl\Exceptions\PermissionAlreadyExists;
use CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\PermissionException;
use CodeMaster\CodeAcl\Traits\HasUsers;
use CodeMaster\CodeAcl\Traits\SetUpModel;
use CodeMaster\CodeAcl\Traits\Sluggable;
use CodeMaster\CodeAcl\Traits\RefreshesCodeAclCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model implements PermissionContract
{
    use SetUpModel, Sluggable, RefreshesCodeAclCache, HasUsers;

    protected $fillable = ['name'];

    /**
     * Model constructor
     *
     * @param array|null $attributes
     */
    public function __construct(array $attributes = [])
    {
        self::$modelData = config('code-acl.models.permission');

        $this->dispatchesEvents = self::$modelData['events'];
        $this->setUp();

        parent::__construct($attributes);
    }

    /**
     * Create a permission
     *
     * @param array $attributes
     *
     * @return \CodeMaster\CodeAcl\Models\Permission
     */
    public static function create(array $attributes): self
    {
        $permission = self::whereName($attributes['name'])->first();

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name']);
        }

        try {
            $permission = tap(static::query()->newModelInstance($attributes), function ($instance) {
                $instance->save();
            });
        } catch (\Exception $exception) {
            throw PermissionException::create($attributes['name'], $exception);
        }

        return $permission;
    }

    /**
     * @inheritDoc
     */
    public static function findById($id): self
    {
        $permission = self::find($id);

        if (! $permission) {
            throw PermissionDoesNotExist::withId($id);
        }

        return $permission;
    }

    /**
     * @inheritDoc
     */
    public static function findByName(string $name): self
    {
        $permission = self::whereName($name)->first();

        if (! $permission) {
            throw PermissionDoesNotExist::withName($name);
        }

        return $permission;
    }

    /**
     * @inheritDoc
     */
    public static function findBySlug(string $slug): self
    {
        $permission = self::whereSlug($slug)->first();

        if (! $permission) {
            throw PermissionDoesNotExist::withSlug($slug);
        }

        return $permission;
    }

    /**
     * @inheritDoc
     */
    public static function findOrCreate(string $name): self
    {
        $permission = self::whereName($name)->first();

        if (! $permission) {
            return self::create(['name'=> $name]);
        }

        return $permission;
    }

    /**
     * @inheritDoc
     */
    public static function getStoredNames(): Collection
    {
        return new Collection(self::all()->pluck('name'));
    }

    /**
     * A model may have multiple roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(config('code-acl.models.role.class'));
    }
}
