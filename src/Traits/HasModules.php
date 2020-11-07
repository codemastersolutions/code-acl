<?php

namespace CodeMaster\CodeAcl\Traits;

use CodeMaster\CodeAcl\CodeAclRegister;
use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use CodeMaster\CodeAcl\Exceptions\ModuleDoesNotExist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;

trait HasModules
{
    /** @var CodeMaster\CodeAcl\Contracts\Module */
    private ModuleContract $moduleInstance;

    /** @var string */
    private static string $slugRegex = "/^[a-z0-9]+(?:-[a-z0-9]+)*$/";

    private $module_key_name = "code-acl.models.module.primary_key.name";

    public static function bootHasModules()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            $model->modules()->detach();
        });
    }

    /**
     * Assign the given module to the model.
     *
     * @param array|string|int|\CodeMaster\CodeAcl\Contracts\Module ...$modules
     * @return $this
     */
    public function assignModule(...$modules)
    {
        $modules = collect($modules)->flatten()
            ->map(function ($module) {
                if (empty($module)) {
                    return false;
                }

                return $this->getStoredModules($module);
            })
            ->filter(function ($module) {
                return $module instanceof ModuleContract;
            })
            ->map->id
            ->all();

        $model = $this->getModel();

        if ($model->exists) {
            $this->modules()->syncWithoutDetaching($modules);
            $model->load('modules');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($modules, $model) {
                    static $modelLastFiredOn;
                    if ($modelLastFiredOn !== null && $modelLastFiredOn === $model) {
                        return;
                    }
                    $object->modules()->syncWithoutDetaching($modules);
                    $object->load('modules');
                    $modelLastFiredOn = $object;
                }
            );
        }

        return $this;
    }

    /**
     * An alias to hasModule(), but avoids throwing an exception.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\Module $module
     * @return bool
     * @throws \CodeMaster\CodeAcl\Exceptions\ModuleDoesNotExist
     */
    public function checkModule($module): bool
    {
        try {
            return $this->hasModule($module);
        } catch (ModuleDoesNotExist $e) {
            return false;
        }
    }

    /**
     * @param string|array|int|\CodeMaster\CodeAcl\Contracts\Module|\Illuminate\Database\Eloquent\Collection $modules
     *
     * @return array
     */
    protected function convertToModuleModels($modules): array
    {
        if ($modules instanceof Collection) {
            $modules = $modules->all();
        }

        $modules = is_array($modules) ? $modules : [$modules];

        return array_map(function ($module) {
            if ($module instanceof ModuleContract) {
                return $module;
            }

            return $this->getModuleInstance()->findByName($module);
        }, $modules);
    }

    /**
     * Detech the given module.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Module|\CodeMaster\CodeAcl\Contracts\Module[]|string|string[]|int|int[] ...$modules
     *
     * @return $this
     */
    private function detachModules(...$modules)
    {
        $this->modules()->detach($this->getStoredModules($modules));

        $this->forgetCachedPermissions();

        $this->load('modules');

        return $this;
    }

    /**
     * Return module class
     *
     * @return \CodeMaster\CodeAcl\Contracts\Module
     */
    public function getModuleInstance(): ModuleContract
    {
        if ((! isset($this->moduleInstance)) ||
            (! $this->moduleInstance instanceof ModuleContract)) {
            $this->moduleInstance = app(CodeAclRegister::class)->getModuleClass();
        }

        return $this->moduleInstance;
    }

    /**
     * Find modules.
     *
     * @param string|string[]|int|int[]|\CodeMaster\CodeAcl\Contracts\Module|\CodeMaster\CodeAcl\Contracts\Module[]|\Illuminate\Database\Eloquent\Collection $modules
     * @return \CodeMaster\CodeAcl\Contracts\Module|\CodeMaster\CodeAcl\Contracts\Module[]|\Illuminate\Database\Eloquent\Collection
     * @throws \CodeMaster\CodeAcl\Exceptions\ModuleDoesNotExist
     */
    protected function getStoredModules($modules)
    {
        $moduleClass = $this->getModuleInstance();
        $isUuid = is_string($modules) ? Uuid::isValid($modules) : false;
        $isSlug = is_string($modules) ? preg_match(self::$slugRegex, $modules) : false;

        if (is_string($modules) && !$isUuid && !$isSlug) {
            $modules = $moduleClass->findByName($modules);
        }

        if (is_string($modules) && !$isUuid && $isSlug) {
            $modules = $moduleClass->findBySlug($modules);
        }

        if (is_int($modules) || $isUuid) {
            $modules = $moduleClass->findById($modules);
        }

        if (is_array($modules)) {
            $modules = $moduleClass
                ->whereIn('id', $modules)
                ->orWhereIn('name', $modules)
                ->orWhereIn('slug', $modules)
                ->get();
        }

        if (
            (($modules instanceof Collection) && ($modules->count() === 0)) ||
            ((!$modules instanceof Collection) && (! $modules instanceof ModuleContract) )
        ) {
            throw new ModuleDoesNotExist;
        }

        return $modules;
    }

    /**
     * Retrieve all related modules name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getModulesName(): Collection
    {
        return new Collection($this->modules()->pluck('name'));
    }

    /**
     * Detach the given modules.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Module|\CodeMaster\CodeAcl\Contracts\Module[]|string|string[]|int|int[] ...$modules
     * @return $this
     */
    public function giveModules(...$modules): self
    {
        return $this->assignModule($modules);
    }

    /**
     * Determine if the model has any of the given modules.
     *
     * @param array ...$modules
     * @return bool
     * @throws \Exception
     */
    public function hasAnyModule(...$modules): bool
    {
        $modules = collect($modules)->flatten();

        foreach ($modules as $module) {
            if ($this->checkModule($module)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has the given module.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\Module $module
     * @return bool
     * @throws \CodeMaster\CodeAcl\Exceptions\ModuleDoesNotExist
     */
    public function hasModule($module): bool
    {
        if (method_exists($this, 'modules') && !empty($module)) {
            $module = $this->getStoredModules($module);

            return $this->modules()->get()
                        ->contains(config($this->module_key_name), $module->id);
        }

        return false;
    }

    /**
     * Revoke the given modules.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Module|\CodeMaster\CodeAcl\Contracts\Module[]|string|string[]|int|int[] ...$modules
     * @return \CodeMaster\CodeAcl\Contracts\Module
     */
    public function revokeModules(...$modules): self
    {
        $modules = collect($modules)->flatten();

        foreach ($modules as $module) {
            $this->detachModules($module);
        }

        return $this;
    }

    /**
     * A model may have multiple modules.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(config('code-acl.models.module.class'));
    }

    /**
     * Scope the model query to certain modules only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array|int|\CodeMaster\CodeAcl\Contracts\Module|\Illuminate\Database\Eloquent\Collection $modules
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModule(Builder $query, $modules): Builder
    {
        if ($modules instanceof Collection) {
            $modules = $modules->all();
        }

        if (! is_array($modules)) {
            $modules = [$modules];
        }

        $modules = array_map(function ($module) {
            if ($module instanceof ModuleContract) {
                return $module;
            }

            return $this->getStoredModules($module);
        }, $modules);

        return $query->whereHas('modules', function (Builder $subQuery) use ($modules) {
            $subQuery->whereIn(
                implode('.', [
                    config('code-acl.models.module.table'),
                    config($this->module_key_name)
                ]), \array_column($modules, config($this->module_key_name)));
        });
    }
}
