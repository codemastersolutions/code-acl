<?php

namespace CodeMaster\CodeAcl;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Contracts\System as SystemContract;
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
    protected $permissionClass, $roleClass, $systemClass, $moduleClass;

    /** @var \Illuminate\Database\Eloquent\Collection */
    protected $permissions, $modules, $systems;

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
        $this->systemClass = config('code-acl.models.system.class');
        $this->moduleClass = config('code-acl.models.module.class');
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
     * Register the module check method on the gate.
     * We resolve the Gate fresh here, for benefit of long-running instances.
     *
     * @return bool
     */
    public function registerModules(): bool
    {
        app(Gate::class)->before(function (Authorizable $user, string $ability) {
            if (method_exists($user, 'checkModule') && $user->checkModule($ability)) {
                return true;
            }
        });

        return true;
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
            if (method_exists($user, 'checkPermission') && $user->checkPermission($ability)) {
                return true;
            }
        });

        return true;
    }

    /**
     * Register the system check method on the gate.
     * We resolve the Gate fresh here, for benefit of long-running instances.
     *
     * @return bool
     */
    public function registerSystems(): bool
    {
        app(Gate::class)->before(function (Authorizable $user, string $ability) {
            if (method_exists($user, 'checkSystem') && $user->checkSystem($ability)) {
                return true;
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
     * Clear class modules.
     * This is only intended to be called by the CodeAclServiceProvider on boot,
     * so that long-running instances like Swoole don't keep old data in memory.
     */
    public function clearClassModules()
    {
        $this->modules = null;

        return $this;
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
     * Clear class roles.
     * This is only intended to be called by the CodeAclServiceProvider on boot,
     * so that long-running instances like Swoole don't keep old data in memory.
     */
    public function clearClassRoles()
    {
        $this->roles = null;

        return $this;
    }

    /**
     * Clear class systems.
     * This is only intended to be called by the CodeAclServiceProvider on boot,
     * so that long-running instances like Swoole don't keep old data in memory.
     */
    public function clearClassSystems()
    {
        $this->systems = null;

        return $this;
    }

    /**
     * Get an instance of the module class.
     *
     * @return \CodeMaster\CodeAcl\Contracts\Module
     */
    public function getModuleClass(): ModuleContract
    {
        return app($this->moduleClass);
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
     * Get an instance of the role class.
     *
     * @return \CodeMaster\CodeAcl\Contracts\Role
     */
    public function getRoleClass(): RoleContract
    {
        return app($this->roleClass);
    }

    /**
     * Get an instance of the system class.
     *
     * @return \CodeMaster\CodeAcl\Contracts\System
     */
    public function getSystemClass(): SystemContract
    {
        return app($this->systemClass);
    }

    /**
     * Set an instance of the module class.
     *
     * @return self
     */
    public function setModuleClass($moduleClass)
    {
        $this->moduleClass = $moduleClass;

        return $this;
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
     * Set an instance of the system class.
     *
     * @return self
     */
    public function setSystemClass($systemClass)
    {
        $this->systemClass = $systemClass;

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
