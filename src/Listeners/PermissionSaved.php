<?php

namespace CodeMaster\CodeAcl\Listeners;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use CodeMaster\CodeAcl\Events\PermissionSaved as PermissionSavedEvent;
use CodeMaster\CodeLog\Logging\Log;

class PermissionSaved
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
     * @param  \CodeMaster\CodeAcl\Events\PermissionSaved $event
     * @return void
     */
    public function handle(PermissionSavedEvent $event)
    {
        Log::info('permission-created', ['permissions' => $event->permission->toArray()]);
    }
}
