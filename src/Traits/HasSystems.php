<?php

namespace CodeMaster\CodeAcl\Traits;

use CodeMaster\CodeAcl\CodeAclRegister;
use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use CodeMaster\CodeAcl\Exceptions\SystemDoesNotExist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ramsey\Uuid\Uuid;

trait HasSystems
{
    /** @var CodeMaster\CodeAcl\Contracts\System */
    private SystemContract $systemInstance;

    /** @var string */
    private static string $slugRegex = "/^[a-z0-9]+(?:-[a-z0-9]+)*$/";

    private $system_key_name = "code-acl.models.system.primary_key.name";

    public static function bootHasSystems()
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            $model->systems()->detach();
        });
    }

    /**
     * Assign the given system to the model.
     *
     * @param array|string|int|\CodeMaster\CodeAcl\Contracts\System ...$systems
     * @return $this
     */
    public function assignSystem(...$systems)
    {
        $systems = collect($systems)->flatten()
            ->map(function ($system) {
                if (empty($system)) {
                    return false;
                }

                return $this->getStoredSystems($system);
            })
            ->filter(function ($system) {
                return $system instanceof SystemContract;
            })
            ->map->id
            ->all();

        $model = $this->getModel();

        if ($model->exists) {
            $this->systems()->syncWithoutDetaching($systems);
            $model->load('systems');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($systems, $model) {
                    static $modelLastFiredOn;
                    if ($modelLastFiredOn !== null && $modelLastFiredOn === $model) {
                        return;
                    }
                    $object->systems()->syncWithoutDetaching($systems);
                    $object->load('systems');
                    $modelLastFiredOn = $object;
                }
            );
        }

        return $this;
    }

    /**
     * An alias to hasSystem(), but avoids throwing an exception.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\System $system
     * @return bool
     * @throws \CodeMaster\CodeAcl\Exceptions\SystemDoesNotExist
     */
    public function checkSystem($system): bool
    {
        try {
            return $this->hasSystem($system);
        } catch (SystemDoesNotExist $e) {
            return false;
        }
    }

    /**
     * @param string|array|int|\CodeMaster\CodeAcl\Contracts\System|\Illuminate\Database\Eloquent\Collection $systems
     *
     * @return array
     */
    protected function convertToSystemModels($systems): array
    {
        if ($systems instanceof Collection) {
            $systems = $systems->all();
        }

        $systems = is_array($systems) ? $systems : [$systems];

        return array_map(function ($system) {
            if ($system instanceof SystemContract) {
                return $system;
            }

            return $this->getSystemInstance()->findByName($system);
        }, $systems);
    }

    /**
     * Detech the given system.
     *
     * @param \CodeMaster\CodeAcl\Contracts\System|\CodeMaster\CodeAcl\Contracts\System[]|string|string[]|int|int[] ...$systems
     *
     * @return $this
     */
    private function detachSystems(...$systems)
    {
        $this->systems()->detach($this->getStoredSystems($systems));

        $this->load('systems');

        return $this;
    }

    /**
     * Return system class
     *
     * @return \CodeMaster\CodeAcl\Contracts\System
     */
    public function getSystemInstance(): SystemContract
    {
        if ((! isset($this->systemInstance)) ||
            (! $this->systemInstance instanceof SystemContract)) {
            $this->systemInstance = app(CodeAclRegister::class)->getSystemClass();
        }

        return $this->systemInstance;
    }

    /**
     * Find systems.
     *
     * @param string|string[]|int|int[]|\CodeMaster\CodeAcl\Contracts\System|\CodeMaster\CodeAcl\Contracts\System[]|\Illuminate\Database\Eloquent\Collection $systems
     * @return \CodeMaster\CodeAcl\Contracts\System|\CodeMaster\CodeAcl\Contracts\System[]|\Illuminate\Database\Eloquent\Collection
     * @throws \CodeMaster\CodeAcl\Exceptions\SystemDoesNotExist
     */
    protected function getStoredSystems($systems)
    {
        $systemClass = $this->getSystemInstance();
        $isUuid = is_string($systems) ? Uuid::isValid($systems) : false;
        $isSlug = is_string($systems) ? preg_match(self::$slugRegex, $systems) : false;

        if (is_string($systems) && !$isUuid && !$isSlug) {
            $systems = $systemClass->findByName($systems);
        }

        if (is_string($systems) && !$isUuid && $isSlug) {
            $systems = $systemClass->findBySlug($systems);
        }

        if (is_int($systems) || $isUuid) {
            $systems = $systemClass->findById($systems);
        }

        if (is_array($systems)) {
            $systems = $systemClass
                ->whereIn('id', $systems)
                ->orWhereIn('name', $systems)
                ->orWhereIn('slug', $systems)
                ->get();
        }

        if (
            (($systems instanceof Collection) && ($systems->count() === 0)) ||
            ((!$systems instanceof Collection) && (! $systems instanceof SystemContract) )
        ) {
            throw new SystemDoesNotExist;
        }

        return $systems;
    }

    /**
     * Retrieve all related systems name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSystemsName(): Collection
    {
        return new Collection($this->systems()->pluck('name'));
    }

    /**
     * Detach the given systems.
     *
     * @param \CodeMaster\CodeAcl\Contracts\System|\CodeMaster\CodeAcl\Contracts\System[]|string|string[]|int|int[] ...$systems
     * @return $this
     */
    public function giveSystems(...$systems): self
    {
        return $this->assignSystem($systems);
    }

    /**
     * Determine if the model has any of the given systems.
     *
     * @param array ...$systems
     * @return bool
     * @throws \Exception
     */
    public function hasAnySystem(...$systems): bool
    {
        $systems = collect($systems)->flatten();

        foreach ($systems as $system) {
            if ($this->checkSystem($system)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has the given system.
     *
     * @param string|int|\CodeMaster\CodeAcl\Contracts\System $system
     * @return bool
     * @throws \CodeMaster\CodeAcl\Exceptions\SystemDoesNotExist
     */
    public function hasSystem($system): bool
    {
        $system = $this->getStoredSystems($system);

        if (! $system instanceof SystemContract) {
            return false;
        }

        return $this->systems()->get()
                    ->contains(config($this->system_key_name), $system->id);
    }

    /**
     * Revoke the given systems.
     *
     * @param \CodeMaster\CodeAcl\Contracts\System|\CodeMaster\CodeAcl\Contracts\System[]|string|string[]|int|int[] ...$systems
     * @return \CodeMaster\CodeAcl\Contracts\System
     */
    public function revokeSystems(...$systems): self
    {
        $systems = collect($systems)->flatten();

        foreach ($systems as $system) {
            $this->detachSystems($system);
        }

        return $this;
    }

    /**
     * A model may have multiple systems.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function systems(): BelongsToMany
    {
        return $this->belongsToMany(config('code-acl.models.system.class'));
    }

    /**
     * Scope the model query to certain systems only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array|int|\CodeMaster\CodeAcl\Contracts\System|\Illuminate\Database\Eloquent\Collection $systems
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSystem(Builder $query, $systems): Builder
    {
        if ($systems instanceof Collection) {
            $systems = $systems->all();
        }

        if (! is_array($systems)) {
            $systems = [$systems];
        }

        $systems = array_map(function ($system) {
            if ($system instanceof SystemContract) {
                return $system;
            }

            return $this->getStoredSystems($system);
        }, $systems);

        return $query->whereHas('systems', function (Builder $subQuery) use ($systems) {
            $subQuery->whereIn(
                implode('.', [
                    config('code-acl.models.system.table'),
                    config($this->system_key_name)
                ]), \array_column($systems, config($this->system_key_name)));
        });
    }
}
