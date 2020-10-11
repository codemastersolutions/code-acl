<?php

namespace CodeMaster\CodeAcl\Events;

use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class RoleUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \CodeMaster\CodeAcl\Contracts\Role
     */
    public $role;

    /**
     * Create a new event instance.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Role $role
     */
    public function __construct(RoleContract $role)
    {
        $this->role = $role;
    }
}
