<?php

namespace CodeMaster\CodeAcl;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\RoleDoesNotExist;
use CodeMaster\CodeAcl\Logging\Log;
use CodeMaster\CodeAcl\Models\User;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\Uuid;

class CodeAclServiceProvider extends ServiceProvider
{
    public function boot(CodeAclRegister $codeAclLoader, Filesystem $filesystem)
    {
        $this->register();
        $this->registerCommands();
        $this->registerMacroHelpers();
        $this->registerMigrations();
        $this->registerModelBindings();
        $this->registerPublishing($filesystem);
        $this->registerRoutes();

        $codeAclLoader->clearClassPermissions();
        $codeAclLoader->registerPermissions();

        $this->app->singleton(CodeAclRegister::class, function ($app) use ($codeAclLoader) {
            return $codeAclLoader;
        });
    }

    /**
     * Register the package.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->mergeConfigFrom(__DIR__.'/../config/code-acl.php', 'code-acl');
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands([
            Commands\CacheReset::class,
            Commands\CreateRole::class,
            Commands\CreatePermission::class,
            // Commands\CreateModule::class,
            // Commands\CreateSystem::class,
            // Commands\Show::class,
        ]);
    }

    /**
     * Register the package's macro helpers.
     *
     * @return void
     */
    protected function registerMacroHelpers()
    {
        if (! method_exists(Route::class, 'macro')) { // Lumen
            return;
        }

        Route::macro('permission', function ($permissions = []) {
            if (! is_array($permissions)) {
                $permissions = [$permissions];
            }

            $permissions = implode('|', $permissions);

            $this->middleware("permission:$permissions");

            return $this;
        });

        Route::macro('role', function ($roles = []) {
            if (! is_array($roles)) {
                $roles = [$roles];
            }

            $roles = implode('|', $roles);

            $this->middleware("role:$roles");

            return $this;
        });
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });

        Route::bind('permission', function ($value) {
            try {
                $permissionModel = app(PermissionContract::class);
                return Uuid::isValid($value) ? $permissionModel::findById($value) : $permissionModel::findBySlug($value);
            } catch(PermissionDoesNotExist $e) {
                abort(404);
            }
        });

        Route::bind('role', function ($value) {
            try {
                $roleModel = app(RoleContract::class);
                return Uuid::isValid($value) ? $roleModel::findById($value) : $roleModel::findBySlug($value);
            } catch(RoleDoesNotExist $e) {
                abort(404);
            }
        });

        Route::bind('user', function ($value) {
           return app(UserContract::class)::whereId($value)->first() ?? abort(404);
        });
    }

    /**
     * Get the Telescope route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        return [
            'domain' => config('code-acl.defaults.code-acl.domain', null),
            'namespace' => 'CodeMaster\CodeAcl\Http\Controllers',
            'prefix' => 'api/'.config('code-acl.defaults.code-acl.path'),
            'middleware' => config('code-acl.defaults.code-acl.middleware', 'api'),
        ];
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing(Filesystem $filesystem)
    {
        if (function_exists('config_path')) { // function not available and 'publish' not relevant in Lumen
            $this->publishes([
                __DIR__.'/../config/code-acl.php' => config_path('code-acl.php'),
            ], 'codeacl-config');

            $this->publishes([
                __DIR__.'/Database/Migrations/2014_10_12_111000_create_permissions_tables.php' =>
                    $this->getMigrationFileName(
                        $filesystem,
                        $this->app->config['code-acl.models.permission.table'],
                        '2014_10_12_111000'),
                __DIR__.'/Database/Migrations/2014_10_12_112000_create_roles_tables.php' =>
                    $this->getMigrationFileName(
                        $filesystem,
                        $this->app->config['code-acl.models.role.table'],
                        '2014_10_12_112000'),
            ], 'codeacl-migrations');
        }
    }

    /**
     * Register the package's models bind.
     *
     * @return void
     */
    protected function registerModelBindings()
    {
        $models = $this->app->config['code-acl.models'];

        if (! $models) {
            return;
        }

        $this->app->bind(PermissionContract::class, $models['permission']['class']);
        $this->app->bind(RoleContract::class, $models['role']['class']);
        $this->app->bind(UserContract::class, User::class);
        $this->app->bind(LogContract::class, Log::class);
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @param string $tableName
     * @param string|null $timestamp
     *
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem, string $tableName, string $timestamp = null): string
    {
        $timestamp = $timestamp ?: date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $tableName) {
                return $filesystem->glob($path."*_create_{$tableName}_tables.php");
            })->push($this->app->databasePath()."/migrations/{$timestamp}_create_{$tableName}_tables.php")
            ->first();
    }
}
