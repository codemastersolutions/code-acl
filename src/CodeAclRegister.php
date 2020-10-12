<?php

namespace CodeMaster\CodeAcl;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Collection;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CodeAclRegister
{
    /** @var \Illuminate\Contracts\Cache\Repository */
    protected $cache;

    /** @var \Illuminate\Cache\CacheManager */
    protected $cacheManager;

    /** @var string */
    protected $permissionClass, $roleClass;

    /** @var \Illuminate\Database\Eloquent\Collection */
    protected $permissions;

    /** @var \DateInterval|int */
    public static $cacheExpirationTime;

    /** @var string */
    public static $cacheKey;

    /** @var string */
    public static $cacheModelKey;

    /**
     * CodeAclRegister constructor.
     *
     * @param \Illuminate\Cache\CacheManager $cacheManager
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->permissionClass = config('code-acl.models.permission.class');
        $this->roleClass = config('code-acl.models.role.class');
        $this->cacheManager = $cacheManager;
        $this->initializeCache();
    }

    protected function initializeCache()
    {
        self::$cacheExpirationTime = config('code-acl.cache.expiration_time', config('code-acl.cache_expiration_time'));

        self::$cacheKey = config('code-acl.cache.key');
        self::$cacheModelKey = config('code-acl.cache.model_key');

        $this->cache = $this->getCacheStoreFromConfig();
    }

    protected function getCacheStoreFromConfig(): \Illuminate\Contracts\Cache\Repository
    {
        // the 'default' fallback here is from the code-acl.php config file, where 'default' means to use config(cache.default)
        $cacheDriver = $this->getCacheDriver();

        // when 'default' is specified, no action is required since we already have the default instance
        if ($cacheDriver === 'default') {
            return $this->cacheManager->store();
        }

        // if an undefined cache store is specified, fallback to 'array' which is Laravel's closest equiv to 'none'
        if (! \array_key_exists($cacheDriver, config('cache.stores'))) {
            $cacheDriver = 'array';
        }

        return $this->cacheManager->store($cacheDriver);
    }

    protected function getCacheDriver()
    {
        return config('code-acl.cache.store', 'default');
    }

    /**
     * Register the permission check method on the gate.
     * We resolve the Gate fresh here, for benefit of long-running instances.
     *
     * @return bool
     */
    public function registerPermissions(): bool
    {
        app(Gate::class)->before(function (Authorizable $user, string $ability) {
            if (method_exists($user, 'checkPermission')) {
                if ($user->checkPermission($ability)) {
                    return true;
                }
            }

            if (method_exists($user, 'roles')) {
                $roles = $user->roles()->get();

                $roles->map(function ($role) use ($ability) {
                    if (empty($role)) {
                        return null;
                    }

                    $permissions = $role->permissions()->get();

                    $permissions->map(function ($permission) use ($ability) {
                        if (empty($permission)) {
                            return null;
                        }

                        return (($permission->slug === $ability) || ($permission->name === $ability));
                    });
                });
            }
        });

        return true;
    }

    /**
     * Get the permissions based on the passed params.
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPermissions(array $params = []): Collection
    {
        if ($this->permissions === null) {
            $this->permissions = $this->cache->remember(self::$cacheKey, self::$cacheExpirationTime, function () {
                return $this->getPermissionClass()::all();
            });
        }

        $localPermissions = [];

        foreach ($params as $attr => $value) {
            $p = $this->permissions->where($attr, $value)->first();
            if ($p instanceof PermissionContract) {
                array_push($localPermissions, $p);
            }
        }

        return new Collection($localPermissions);
    }

    /**
     * Flush the cache.
     */
    public function forgetCachedPermissions()
    {
        $this->permissions = null;

        return $this->cache->forget(self::$cacheKey);
    }

    /**
     * Clear class permissions.
     * This is only intended to be called by the CodeAclServiceProvider on boot,
     * so that long-running instances like Swoole don't keep old data in memory.
     */
    public function clearClassPermissions()
    {
        $this->permissions = null;

        return $this;
    }

    /**
     * Get an instance of the permission class.
     *
     * @return \CodeMaster\CodeAcl\Contracts\Permission
     */
    public function getPermissionClass(): PermissionContract
    {
        return app($this->permissionClass);
    }

    /**
     * Set an instance of the permission class.
     *
     * @return self
     */
    public function setPermissionClass($permissionClass)
    {
        $this->permissionClass = $permissionClass;

        return $this;
    }

    /**
     * Get an instance of the role class.
     *
     * @return \CodeMaster\CodeAcl\Contracts\Role
     */
    public function getRoleClass(): RoleContract
    {
        return app($this->roleClass);
    }

    /**
     * Set an instance of the role class.
     *
     * @return self
     */
    public function setRoleClass($roleClass)
    {
        $this->roleClass = $roleClass;

        return $this;
    }

    /**
     * Get an instance of the role class.
     *
     * @return \Monolog\Logger
     */
    public function getLogger($level = Logger::DEBUG): Logger
    {
        $log = new Logger('code-acl');
        $log->pushHandler(new StreamHandler(storage_path('logs/code-acl.log'), $level));
        return $log;
    }

    /**
     * Get the instance of the Cache Store.
     *
     * @return \Illuminate\Contracts\Cache\Store
     */
    public function getCacheStore(): \Illuminate\Contracts\Cache\Store
    {
        return $this->cache->getStore();
    }
}
