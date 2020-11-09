<?php

namespace CodeMaster\CodeAcl\Listeners\Role;

use CodeMaster\CodeAcl\Contracts\Role as RoleContract;
use CodeMaster\CodeAcl\Events\Role\RoleRetrieved as RoleRetrievedEvent;
use CodeMaster\CodeLog\Logging\Log;

class RoleRetrieved
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
     * @param  \CodeMaster\CodeAcl\Events\Role\RoleRetrieved $event
     * @return void
     */
    public function handle(RoleRetrievedEvent $event)
    {
        Log::info('role-retrieved', ['roles' => $event->role->toArray()]);
    }
}
