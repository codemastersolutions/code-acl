<?php

namespace CodeMaster\CodeAcl\Traits;

use CodeMaster\CodeAcl\CodeAclRegister;
use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\RoleDoesNotExist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;

trait HasPermissions
{
    /** @var CodeMaster\CodeAcl\Contracts\Permission */
    private PermissionContract $permissionInstance;

    /** @var string */
    private static string $slugRegex = "/^[a-z0-9]+(?:-[a-z0-9]+)*$/";

    private $permission_key_name = "code-acl.models.permission.primary_key.name";

    public static function bootHasPermissions()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            $model->permissions()->detach();
        });
    }

    /**
     * An alias to hasPermission(), but avoids throwing an exception.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\Permission $permission
     *
     * @return bool
     */
    public function checkPermission($permission): bool
    {
        try {
            return $this->hasPermission($permission) || $this->hasPermissionViaRole($permission);
        } catch (PermissionDoesNotExist $e) {
            return false;
        } catch (RoleDoesNotExist $e) {
            return false;
        }
    }

    /**
     * @param string|array|\CodeMaster\CodeAcl\Contracts\Permission|\Illuminate\Database\Eloquent\Collection $permissions
     *
     * @return array
     */
    protected function convertToPermissionModels($permissions): array
    {
        if ($permissions instanceof Collection) {
            $permissions = $permissions->all();
        }

        $permissions = is_array($permissions) ? $permissions : [$permissions];

        return array_map(function ($permission) {
            if ($permission instanceof PermissionContract) {
                return $permission;
            }

            return $this->getPermissionInstance()->findByName($permission);
        }, $permissions);
    }

    /**
     * @inheritDoc
     */
    private function detachPermissions($permissions): self
    {
        $this->permissions()->detach($this->getStoredPermissions($permissions));

        $this->forgetCachedPermissions();

        $this->load('permissions');

        return $this;
    }

    /**
     * Forget the cached permissions.
     */
    public function forgetCachedPermissions(): void
    {
        app(CodeAclRegister::class)->forgetCachedPermissions();
    }

    /**
     * Return all the permissions the model has, both directly and via roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPermissions(): Collection
    {
        /** @var Collection $permissions */
        $permissions = $this->permissions()->get();

        if ($this->roles) {
            $permissions = $permissions->merge($this->getPermissionsViaRoles());
        }

        return $permissions->sort()->values();
    }

    /**
     * Return permission class
     *
     * @return \CodeMaster\CodeAcl\Contracts\Permission
     */
    public function getPermissionInstance(): PermissionContract
    {
        if ((! isset($this->permissionInstance)) ||
            (! $this->permissionInstance instanceof PermissionContract)) {
            $this->permissionInstance = app(CodeAclRegister::class)->getPermissionClass();
        }

        return $this->permissionInstance;
    }

    /**
     * Retrieve all related permissions name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPemissionsName(): Collection
    {
        return new Collection($this->permissions()->pluck('name'));
    }


    /**
     * Return all the permissions the model has via roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPermissionsViaRoles(): Collection
    {
        return new Collection($this->loadMissing('roles', 'roles.permissions')
            ->roles->flatMap(function ($role) {
                return $role->permissions;
            })->sort()->values());
    }

    /**
     * Find permissions.
     *
     * @param string|int|array|\CodeMaster\CodeAcl\Contracts\Permission|\Illuminate\Database\Eloquent\Collection ...$permissions
     * @return \CodeMaster\CodeAcl\Contracts\Permission|\CodeMaster\CodeAcl\Contracts\Permission[]|\Illuminate\Database\Eloquent\Collection
     * @throws \CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist
     */
    protected function getStoredPermissions($permissions)
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
            ((!$permissions instanceof Collection) && (! $permissions instanceof PermissionContract) )
        ) {
            throw new PermissionDoesNotExist;
        }

        return $permissions;
    }

    /**
     * Grant the given permission(s) to a role.
     *
     * @param string|int|array|\CodeMaster\CodeAcl\Contracts\Permission|\Illuminate\Database\Eloquent\Collection ...$permissions
     * @return $this
     */
    public function givePermissions(...$permissions): self
    {
        $permissions = collect($permissions)->flatten()
            ->map(function ($permission) {
                if (empty($permission)) {
                    return false;
                }

                return $this->getStoredPermissions($permission);
            })
            ->filter(function ($permission) {
                return $permission instanceof PermissionContract;
            })
            ->map->id
            ->all();

            $model = $this->getModel();

        if ($model->exists) {
            $this->permissions()->syncWithoutDetaching($permissions);
            $model->load('permissions');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($permissions, $model) {
                    static $modelLastFiredOn;
                    if ($modelLastFiredOn !== null && $modelLastFiredOn === $model) {
                        return;
                    }
                    $object->permissions()->syncWithoutDetaching($permissions);
                    $object->load('permissions');
                    $modelLastFiredOn = $object;
                }
            );
        }

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Check if the model has all of the requested Direct permissions.
     *
     * @param array ...$permissions
     *
     * @return bool
     */
    public function hasAllDirectPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if (! $this->hasDirectPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the model has all of the given permissions.
     *
     * @param array ...$permissions
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAllPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if (! $this->checkPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the model has any of the given permissions.
     *
     * @param array ...$permissions
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAnyPermission(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if ($this->checkPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has the given permission.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\Permission $permission
     * @return bool
     * @throws \CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist
     */
    public function hasDirectPermission($permission): bool
    {
        $permission = $this->getStoredPermissions($permission);

        if (! $permission instanceof PermissionContract) {
            return false;
        }

        return $this->getAllPermissions()
                    ->contains(config($this->permission_key_name), $permission->id);
    }

    /**
     * Determine if the model may perform the given permission.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\Permission $permission
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasPermission($permission): bool
    {
        if (method_exists($this, 'permissions') && !empty($permission)) {
            $permission = $this->getStoredPermissions($permission);

            return $this->permissions()->get()
            ->contains(config($this->permission_key_name), $permission->id);
        }

        return false;
    }

    /**
     * Determine if the model has, via roles, the given permission.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\Permission $permission
     * @return bool
     */
    protected function hasPermissionViaRole($permission): bool
    {
        if (method_exists($this, 'hasRole')) {
            $permission = $this->getStoredPermissions($permission);
            return $this->hasRole($permission->roles);
        }

        return false;
    }

    /**
     * A model may have multiple direct permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(config('code-acl.models.permission.class'));
    }

     /**
     * Revoke all the permission.
     *
     * @return $this
     */
    public function revokeAllPermissions(): self
    {
        $permissions = $this->permissions()->get();

        return $this->detachPermissions($permissions);
    }

    /**
     * Revoke the permission.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Permission|string|int $permission
     * @return $this
     */
    public function revokePermission($permission): self
    {
        return $this->detachPermissions($permission);
    }

    /**
     * Revoke the permissions.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Permission|\CodeMaster\CodeAcl\Contracts\Permission[]|string|string[]|int[] ...$permissions
     * @return $this
     */
    public function revokePermissions(...$permissions): self
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            $this->detachPermissions($permission);
        }

        return $this;
    }

    /**
     * Scope the model query to certain permissions only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|int|array|\CodeMaster\CodeAcl\Contracts\Permission|\Illuminate\Database\Eloquent\Collection $permissions
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePermission(Builder $query, $permissions): Builder
    {
        $permissions = $this->convertToPermissionModels($permissions);

        $rolesWithPermissions = [];

        if ($this->roles) {
            $rolesWithPermissions = array_unique(array_reduce($permissions, function ($result, $permission) {
                return array_merge($result, $permission->roles->all());
            }, []));
        }

        return $query->where(function (Builder $query) use ($permissions, $rolesWithPermissions) {
            $query->whereHas('permissions', function (Builder $subQuery) use ($permissions) {
                $subQuery->whereIn(
                    implode('.', [
                        config('code-acl.models.permission.table'),
                        config($this->permission_key_name)
                    ]), \array_column($permissions, config($this->permission_key_name)));
            });

            if (count($rolesWithPermissions) > 0) {
                $query->orWhereHas('roles', function (Builder $subQuery) use ($rolesWithPermissions) {
                    $subQuery->whereIn(
                        implode('.', [
                            config('code-acl.models.role.table'),
                            config('code-acl.models.role.primary_key.name')
                        ]), \array_column($rolesWithPermissions, config('code-acl.models.role.primary_key.name')));
                });
            }
        });
    }
}
