<?php

namespace CodeMaster\CodeAcl\Listeners\Role;

use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Events\Role\RoleSaved as RoleSavedEvent;
use CodeMaster\CodeLog\Logging\Log;

class RoleSaved
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
     * @param  \CodeMaster\CodeAcl\Events\RoleSaved $event
     * @return void
     */
    public function handle(RoleSavedEvent $event)
    {
        Log::info('role-created', ['roles' => $event->role->toArray()]);
    }
}
