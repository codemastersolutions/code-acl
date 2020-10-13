<?php

namespace CodeMaster\CodeAcl\Listeners\Permission;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Events\Permission\PermissionDeleted as PermissionDeletedEvent;
use CodeMaster\CodeLog\Logging\Log;

class PermissionDeleted
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
     * @param  \CodeMaster\CodeAcl\Events\Permission\PermissionDeleted $event
     * @return void
     */
    public function handle(PermissionDeletedEvent $event)
    {
        Log::info('permission-deleted', ['permissions' => $event->permission->toArray()]);
    }
}
