<?php

namespace CodeMaster\CodeAcl\Contracts;

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
}
