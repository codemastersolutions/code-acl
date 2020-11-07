<?php

namespace CodeMaster\CodeAcl\Listeners\Permission;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Events\Permission\PermissionRetrieved as PermissionRetrievedEvent;
use CodeMaster\CodeLog\Logging\Log;

class PermissionRetrieved
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
     * @param  \CodeMaster\CodeAcl\Events\Permission\PermissionRetrieved $event
     * @return void
     */
    public function handle(PermissionRetrievedEvent $event)
    {
        Log::info('permission-retrieved', ['permissions' => $event->permission->toArray()]);
    }
}
