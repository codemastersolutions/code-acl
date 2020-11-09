<?php

namespace CodeMaster\CodeAcl\Listeners\Permission;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Events\Permission\PermissionCreated as PermissionCreatedEvent;
use CodeMaster\CodeLog\Logging\Log;

class PermissionCreated
{
    /** @var \CodeMaster\CodeAcl\Contracts\Permission $permission */
    public PermissionContract $permission;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PermissionContract $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Handle the event.
     *
     * @param  \CodeMaster\CodeAcl\Events\Permission\PermissionCreated $event
     * @return void
     */
    public function handle(PermissionCreatedEvent $event)
    {
        Log::info('permission-created', ['permissions' => $event->permission->toArray()]);
    }
}
