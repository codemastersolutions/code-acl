<?php

namespace CodeMaster\CodeAcl\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface User
{
    /**
     * Grant the given module(s) to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Module|\CodeMaster\CodeAcl\Contracts\Module[]|string|string[]|int|int[] ...$modules
     * @return $this
     */
    public function giveModules(...$modules): self;

    /**
     * Grant the given permission(s) to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Permission|\CodeMaster\CodeAcl\Contracts\Permission[]|string|string[]|int|int[] ...$permissions
     * @return $this
     */
    public function givePermissions(...$permissions): self;

    /**
     * Grant the given role(s) to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role|\CodeMaster\CodeAcl\Contracts\Role[]|string|string[]|int|int[] ...$roles
     * @return $this
     */
    public function giveRoles(...$roles): self;

    /**
     * Grant the given system(s) to a user.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Systems|\CodeMaster\CodeAcl\Contracts\System[]|string|string[]|int|int[] ...$systems
     * @return $this
     */
    public function giveSystems(...$systems): self;

    /**
     * A model may have multiple direct modules.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules(): BelongsToMany;

    /**
     * A model may have multiple direct permissions.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany;

    /**
     * Revoke the module(s).
     *
     * @param \CodeMaster\CodeAcl\Contracts\Module|\CodeMaster\CodeAcl\Contracts\Module[]|string|string[]|int[] ...$modules
     * @return $this
     */
    public function revokeModules(...$modules): self;

    /**
     * Revoke the permission(s).
     *
     * @param \CodeMaster\CodeAcl\Contracts\Permission|\CodeMaster\CodeAcl\Contracts\Permission[]|string|string[]|int[] ...$permissions
     * @return $this
     */
    public function revokePermissions(...$permissions): self;

    /**
     * Revoke the given role(s).
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role|\CodeMaster\CodeAcl\Contracts\Role[]|string|string[]|int|int[] ...$roles
     * @return $this
     */
    public function revokeRoles(...$roles): self;

    /**
     * Revoke the given system(s).
     *
     * @param \CodeMaster\CodeAcl\Contracts\System|\CodeMaster\CodeAcl\Contracts\System[]|string|string[]|int|int[] ...$systems
     * @return $this
     */
    public function revokeSystems(...$systems): self;

    /**
     * A model may have multiple direct roles.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * A model may have multiple direct systems.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function systems(): BelongsToMany;
}
