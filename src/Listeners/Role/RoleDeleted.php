<?php

namespace CodeMaster\CodeAcl\Listeners\Role;

use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Events\Role\RoleDeleted as RoleDeletedEvent;
use CodeMaster\CodeLog\Logging\Log;

class RoleDeleted
{
    /** @var \CodeMaster\CodeAcl\Contracts\Role $role */
    public RoleContract $role;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(RoleContract $role)
    {
        $this->role = $role;
    }

    /**
     * Handle the event.
     *
     * @param  \CodeMaster\CodeAcl\Events\RoleDeleted $event
     * @return void
     */
    public function handle(RoleDeletedEvent $event)
    {
        Log::info('role-deleted', ['roles' => $event->role->toArray()]);
    }
}
