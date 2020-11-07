<?php

namespace CodeMaster\CodeAcl\Listeners\Role;

use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Events\Role\RoleCreated as RoleCreatedEvent;
use CodeMaster\CodeLog\Logging\Log;

class RoleCreated
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
     * @param  \CodeMaster\CodeAcl\Events\RoleCreated $event
     * @return void
     */
    public function handle(RoleCreatedEvent $event)
    {
        Log::info('role-created', ['roles' => $event->role->toArray()]);
    }
}
