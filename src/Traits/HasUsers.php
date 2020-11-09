<?php

namespace CodeMaster\CodeAcl\Traits;

use CodeMaster\CodeAcl\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasUsers
{
    /**
     * A model may have multiple direct permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
