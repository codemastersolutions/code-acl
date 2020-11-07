<?php

namespace CodeMaster\CodeAcl\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Permission
{
    /**
     * Get all of the models from the database.
     *
     * @param  array|mixed  $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all($columns = ['*']);

    /**
     * Find a permission by its id.
     *
     * @param string|int $id
     * @throws \CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist
     * @return \CodeMaster\CodeAcl\Contracts\Permission
     */
    public static function findById($id): self;

    /**
     * Find a permission by its name.
     *
     * @param string $name
     * @throws \CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist
     * @return \CodeMaster\CodeAcl\Contracts\Permission
     */
    public static function findByName(string $name): self;

    /**
     * Find a permission by its name.
     *
     * @param string $slug
     * @throws \CodeMaster\CodeAcl\Exceptions\PermissionDoesNotExist
     * @return \CodeMaster\CodeAcl\Contracts\Permission
     */
    public static function findBySlug(string $slug): self;

    /**
     * Find or Create a permission by its name and guard name.
     *
     * @param string $name
     * @return \CodeMaster\CodeAcl\Contracts\Permission
     */
    public static function findOrCreate(string $name): self;

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage();

    /**
     * Retrieve all stored permissions name
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getStoredNames(): Collection;

    /**
     * A model may have multiple direct permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function users(): BelongsToMany;
}
