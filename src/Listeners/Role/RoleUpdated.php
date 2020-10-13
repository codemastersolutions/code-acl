<?php

namespace CodeMaster\CodeAcl\Listeners\Role;

use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Events\Role\RoleUpdated as RoleUpdatedEvent;
use CodeMaster\CodeLog\Logging\Log;

class RoleUpdated
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
     * @param  \CodeMaster\CodeAcl\Events\RoleUpdated $event
     * @return void
     */
    public function handle(RoleUpdatedEvent $event)
    {
        Log::info('role-updated', ['roles' => $event->role->toArray()]);
    }
}
