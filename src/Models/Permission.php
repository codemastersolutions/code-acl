<?php

namespace CodeMaster\CodeAcl\Models;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Events\PermissionDeleted;
use CodeMaster\CodeAcl\Events\PermissionSaved;
use CodeMaster\CodeAcl\Events\PermissionUpdated;
use CodeMaster\CodeAcl\Exceptions\PermissionAlreadyExists;
use CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\PermissionException;
use CodeMaster\CodeAcl\Traits\SetUpModel;
use CodeMaster\CodeAcl\Traits\Sluggable;
use CodeMaster\CodeAcl\Traits\RefreshesCodeAclCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;

class Permission extends Model implements PermissionContract
{
    use SetUpModel, Sluggable, RefreshesCodeAclCache;

    protected $fillable = ['name'];

    protected $dispatchesEvents = [
        'saved' => PermissionSaved::class,
        'updated' => PermissionUpdated::class,
        'deleted' => PermissionDeleted::class,
    ];

    /** @var string */
    private static string $slugRegex = "/^[a-z0-9]+(?:-[a-z0-9]+)*$/";

    /**
     * Model constructor
     *
     * @param array|null $attributes
     */
    public function __construct(array $attributes = [])
    {
        self::$modelData = config('code-acl.models.permission');

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
    public function getStoredPermission($permission)
    {
        $permission = $this->getStoredPermissions($permission);

        if (($permission instanceof Collection) && ($permission->count() > 0)) {
            return $permission->first();
        }

        if ($permission instanceof PermissionContract) {
            return $permission;
        }

        throw new PermissionDoesNotExist;
    }

    /**
     * @inheritDoc
     */
    public function getStoredPermissions($permissions)
    {
        $permissionClass = $this->getPermissionInstance();
        $isUuid = is_string($permissions) ? Uuid::isValid($permissions) : false;
        $isSlug = is_string($permissions) ? preg_match(self::$slugRegex, $permissions) : false;

        if (is_string($permissions) && !$isUuid && !$isSlug) {
            $permissions = $permissionClass->findByName($permissions);
        }

        if (is_string($permissions) && !$isUuid && $isSlug) {
            $permissions = $permissionClass->findBySlug($permissions);
        }

        if (is_int($permissions) || $isUuid) {
            $permissions = $permissionClass->findById($permissions);
        }

        if (is_array($permissions)) {
            $permissions = $permissionClass
                ->whereIn('id', $permissions)
                ->orWhereIn('name', $permissions)
                ->orWhereIn('slug', $permissions)
                ->get();
        }

        if (
            (($permissions instanceof Collection) && ($permissions->count() === 0)) ||
            ((!$permissions instanceof Collection) && (!$permissions instanceof Permission))
        ) {
            throw new PermissionDoesNotExist;
        }

        return $permissions;
    }

    /**
     * @inheritDoc
     */
    public function getStoredPermissionsName(): Collection
    {
        return $this->all()->pluck('name');
    }

    /**
     * A model may have multiple roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(config('code-acl.models.role.class'));
    }
}
