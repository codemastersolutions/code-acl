<?php

namespace CodeMaster\CodeAcl\Listeners\Permission;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Events\Permission\PermissionUpdated as PermissionUpdatedEvent;
use CodeMaster\CodeLog\Logging\Log;

class PermissionUpdated
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
     * @param  \CodeMaster\CodeAcl\Events\Permission\PermissionUpdated $event
     * @return void
     */
    public function handle(PermissionUpdatedEvent $event)
    {
        Log::info('permission-updated', ['permissions' => $event->permission->toArray()]);
    }
}
