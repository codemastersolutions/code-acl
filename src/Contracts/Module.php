<?php

namespace CodeMaster\CodeAcl\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Module
{
    /**
     * Get all of the models from the database.
     *
     * @param  array|mixed  $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all($columns = ['*']);

    /**
     * Create a module
     *
     * @param array $attributes
     * @return \CodeMaster\CodeAcl\Contracts\Module
     */
    public static function create(array $attributes): self;

    /**
     * Find a module by name.
     *
     * @param string $name
     * @throws \CodeMaster\CodeAcl\Exceptions\ModuleDoesNotExist
     * @return \CodeMaster\CodeAcl\Contracts\Module
     */
    public static function findByName(string $name): self;

    /**
     * Find a module by its name.
     *
     * @param string $slug
     * @throws \CodeMaster\CodeAcl\Exceptions\ModuleDoesNotExist
     * @return \CodeMaster\CodeAcl\Contracts\Module
     */
    public static function findBySlug(string $slug): self;

    /**
     * Find a module by its id.
     *
     * @param string|int $id
     * @throws \CodeMaster\CodeAcl\Exceptions\ModuleDoesNotExist
     * @return \CodeMaster\CodeAcl\Contracts\Module
     */
    public static function findById($id): self;

    /**
     * Find or Create a module by its name and guard name.
     *
     * @param string $name
     * @return \CodeMaster\CodeAcl\Contracts\Module
     */
    public static function findOrCreate(string $name): self;

    /**
     * Retrieve all modules name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getNames(): Collection;

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage();

    /**
     * A modules can be applied to permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany;

    /**
     * Grant the given permission(s) to a module.
     *
     * @param string|array|\CodeMaster\CodeAcl\Contracts\Permission|\Illuminate\Database\Eloquent\Collection ...$permissions
     * @return $this
     */
    public function givePermissions(...$permissions): self;

    /**
     * Revoke the permissions.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Permission|\CodeMaster\CodeAcl\Contracts\Permission[]|string|string[]|int[] ...$permissions
     * @return $this
     */
    public function revokePermissions(...$permissions): self;
}
