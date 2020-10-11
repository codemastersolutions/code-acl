<?php

namespace CodeMaster\CodeAcl\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface User
{
    /**
     * Grant the given permission(s) to a role.
     *
     * @param string|array|\CodeMaster\CodeAcl\Contracts\Permission|\Illuminate\Database\Eloquent\Collection ...$permissions
     * @return $this
     */
    public function givePermissions(...$permissions): self;

    /**
     * Detach the given roles.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role|\CodeMaster\CodeAcl\Contracts\Role[]|string|string[]|int|int[] ...$roles
     * @return $this
     */
    public function giveRoles(...$roles): self;

    /**
     * A model may have multiple direct permissions.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany;

    /**
     * Revoke the permissions.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Permission|\CodeMaster\CodeAcl\Contracts\Permission[]|string|string[]|int[] ...$permissions
     * @return $this
     */
    public function revokePermissions(...$permissions): self;

    /**
     * Revoke the given roles.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role|\CodeMaster\CodeAcl\Contracts\Role[]|string|string[]|int|int[] ...$roles
     * @return \CodeMaster\CodeAcl\Contracts\Role
     */
    public function revokeRoles(...$roles): self;

    /**
     * A model may have multiple direct permissions.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany;
}
