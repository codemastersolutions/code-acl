<?php

namespace CodeMaster\CodeAcl\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface System
{
    /**
     * Get all of the models from the database.
     *
     * @param  array|mixed $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all($columns = ['*']);

    /**
     * Create a system
     *
     * @param array $attributes
     * @return \CodeMaster\CodeAcl\Contracts\System
     */
    public static function create(array $attributes): self;

    /**
     * Find a system by name.
     *
     * @param string $name
     * @throws \CodeMaster\CodeAcl\Exceptions\SystemDoesNotExist
     * @return \CodeMaster\CodeAcl\Contracts\System
     */
    public static function findByName(string $name): self;

    /**
     * Find a system by its name.
     *
     * @param string $slug
     * @throws \CodeMaster\CodeAcl\Exceptions\SystemDoesNotExist
     * @return \CodeMaster\CodeAcl\Contracts\System
     */
    public static function findBySlug(string $slug): self;

    /**
     * Find a system by its id.
     *
     * @param string|int $id
     * @throws \CodeMaster\CodeAcl\Exceptions\SystemDoesNotExist
     * @return \CodeMaster\CodeAcl\Contracts\System
     */
    public static function findById($id): self;

    /**
     * Find or Create a system by its name and guard name.
     *
     * @param string $name
     * @return \CodeMaster\CodeAcl\Contracts\System
     */
    public static function findOrCreate(string $name): self;

    /**
     * Retrieve all systems name
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
     * A systems can be applied to permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany;

    /**
     * Grant the given permission(s) to a system.
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
