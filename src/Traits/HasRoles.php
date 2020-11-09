<?php

namespace CodeMaster\CodeAcl\Traits;

use CodeMaster\CodeAcl\CodeAclRegister;
use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Exceptions\RoleDoesNotExist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;

trait HasRoles
{
    use HasPermissions;

    /** @var CodeMaster\CodeAcl\Contracts\Role */
    private RoleContract $roleInstance;

    /** @var string */
    private static string $slugRegex = "/^[a-z0-9]+(?:-[a-z0-9]+)*$/";

    private $role_key_name = "code-acl.models.role.primary_key.name";

    public static function bootHasRoles()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            $model->roles()->detach();
        });
    }

    /**
     * Assign the given role to the model.
     *
     * @param array|string|int|\CodeMaster\CodeAcl\Contracts\Role ...$roles
     * @return $this
     */
    public function assignRole(...$roles)
    {
        $roles = collect($roles)->flatten()
            ->map(function ($role) {
                if (empty($role)) {
                    return false;
                }

                return $this->getStoredRoles($role);
            })
            ->filter(function ($role) {
                return $role instanceof RoleContract;
            })
            ->map->id
            ->all();

        $model = $this->getModel();

        if ($model->exists) {
            $this->roles()->syncWithoutDetaching($roles);
            $model->load('roles');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($roles, $model) {
                    static $modelLastFiredOn;
                    if ($modelLastFiredOn !== null && $modelLastFiredOn === $model) {
                        return;
                    }
                    $object->roles()->syncWithoutDetaching($roles);
                    $object->load('roles');
                    $modelLastFiredOn = $object;
                }
            );
        }

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * An alias to hasRole(), but avoids throwing an exception.
     *
     * @param array $permissions
     * @return bool
     * @throws \CodeMaster\CodeAcl\Exceptions\RoleDoesNotExist
     */
    public function checkPermissionsInRoles($permissions): bool
    {
        $roles = $this->roles()->get();

        foreach ($roles as $role) {
            if ($role->hasAnyPermission($permissions)) {
                return true;
            }
        }

        return false;
    }

    /**
     * An alias to hasRole(), but avoids throwing an exception.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\Role $role
     * @return bool
     * @throws \CodeMaster\CodeAcl\Exceptions\RoleDoesNotExist
     */
    public function checkRole($role): bool
    {
        try {
            return $this->hasRole($role);
        } catch (RoleDoesNotExist $e) {
            return false;
        }
    }

    /**
     * @param string|array|int|\CodeMaster\CodeAcl\Contracts\Role|\Illuminate\Database\Eloquent\Collection $roles
     *
     * @return array
     */
    protected function convertToRoleModels($roles): array
    {
        if ($roles instanceof Collection) {
            $roles = $roles->all();
        }

        $roles = is_array($roles) ? $roles : [$roles];

        return array_map(function ($role) {
            if ($role instanceof RoleContract) {
                return $role;
            }

            return $this->getRoleInstance()->findByName($role);
        }, $roles);
    }

    /**
     * Detech the given role.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role|\CodeMaster\CodeAcl\Contracts\Role[]|string|string[]|int|int[] ...$roles
     *
     * @return $this
     */
    private function detachRoles(...$roles)
    {
        $this->roles()->detach($this->getStoredRoles($roles));

        $this->forgetCachedPermissions();

        $this->load('roles');

        return $this;
    }

    /**
     * Return role class
     *
     * @return \CodeMaster\CodeAcl\Contracts\Role
     */
    public function getRoleInstance(): RoleContract
    {
        if ((! isset($this->roleInstance)) ||
            (! $this->roleInstance instanceof RoleContract)) {
            $this->roleInstance = app(CodeAclRegister::class)->getRoleClass();
        }

        return $this->roleInstance;
    }

    /**
     * Find roles.
     *
     * @param string|string[]|int|int[]|\CodeMaster\CodeAcl\Contracts\Role|\CodeMaster\CodeAcl\Contracts\Role[]|\Illuminate\Database\Eloquent\Collection $roles
     * @return \CodeMaster\CodeAcl\Contracts\Role|\CodeMaster\CodeAcl\Contracts\Role[]|\Illuminate\Database\Eloquent\Collection
     * @throws \CodeMaster\CodeAcl\Exceptions\RoleDoesNotExist
     */
    public function getStoredRoles($roles)
    {
        $roleClass = $this->getRoleInstance();
        $isUuid = is_string($roles) ? Uuid::isValid($roles) : false;
        $isSlug = is_string($roles) ? preg_match(self::$slugRegex, $roles) : false;

        if (is_string($roles) && !$isUuid && !$isSlug) {
            $roles = $roleClass->findByName($roles);
        }

        if (is_string($roles) && !$isUuid && $isSlug) {
            $roles = $roleClass->findBySlug($roles);
        }

        if (is_int($roles) || $isUuid) {
            $roles = $roleClass->findById($roles);
        }

        if (is_array($roles)) {
            $roles = $roleClass
                ->whereIn('id', $roles)
                ->orWhereIn('name', $roles)
                ->orWhereIn('slug', $roles)
                ->get();
        }

        if (
            (($roles instanceof Collection) && ($roles->count() === 0)) ||
            ((!$roles instanceof Collection) && (! $roles instanceof RoleContract) )
        ) {
            throw new RoleDoesNotExist;
        }

        return $roles;
    }

    /**
     * Retrieve all related roles name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRolesName(): Collection
    {
        return new Collection($this->roles()->pluck('name'));
    }

    /**
     * Detach the given roles.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role|\CodeMaster\CodeAcl\Contracts\Role[]|string|string[]|int|int[] ...$roles
     * @return $this
     */
    public function giveRoles(...$roles): self
    {
        return $this->assignRole($roles);
    }

    /**
     * Determine if the model has any of the given roles.
     *
     * @param array ...$roles
     * @return bool
     * @throws \Exception
     */
    public function hasAnyRole(...$roles): bool
    {
        $roles = collect($roles)->flatten();

        foreach ($roles as $role) {
            if ($this->checkRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has any of the given permissions.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role $role
     * @param array ...$permissions
     * @return bool
     * @throws \Exception
     */
    public static function hasAnyPermissionInRole(RoleContract $role, ...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has the given role.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\Role $role
     * @return bool
     * @throws \CodeMaster\CodeAcl\Exceptions\RoleDoesNotExist
     */
    public function hasDirectRole($role): bool
    {
        $role = $this->getStoredRoles($role);

        if (! $role instanceof RoleContract) {
            return false;
        }

        return $this->roles()->get()
                    ->contains(config($this->role_key_name), $role->id);
    }

    /**
     * Determine if the model may perform the given role.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\Role $role
     * @return bool
     * @throws RoleDoesNotExist
     */
    public function hasRole($role): bool
    {
        return $this->hasDirectRole($role);
    }

    /**
     * Revoke the given roles.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role|\CodeMaster\CodeAcl\Contracts\Role[]|string|string[]|int|int[] ...$roles
     * @return \CodeMaster\CodeAcl\Contracts\Role
     */
    public function revokeRoles(...$roles): self
    {
        $roles = collect($roles)->flatten();

        foreach ($roles as $role) {
            $this->detachRoles($role);
        }

        return $this;
    }

    /**
     * A model may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(config('code-acl.models.role.class'));
    }

    /**
     * Scope the model query to certain roles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array|int|\CodeMaster\CodeAcl\Contracts\Role|\Illuminate\Database\Eloquent\Collection $roles
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRole(Builder $query, $roles): Builder
    {
        if ($roles instanceof Collection) {
            $roles = $roles->all();
        }

        if (! is_array($roles)) {
            $roles = [$roles];
        }

        $roles = array_map(function ($role) {
            if ($role instanceof RoleContract) {
                return $role;
            }

            return $this->getStoredRoles($role);
        }, $roles);

        return $query->whereHas('roles', function (Builder $subQuery) use ($roles) {
            $subQuery->whereIn(
                implode('.', [
                    config('code-acl.models.role.table'),
                    config($this->role_key_name)
                ]), \array_column($roles, config($this->role_key_name)));
        });
    }
}
