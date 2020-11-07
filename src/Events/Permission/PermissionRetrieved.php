<?php

namespace CodeMaster\CodeAcl\Events\Permission;

use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PermissionRetrieved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \CodeMaster\CodeAcl\Contracts\Permission
     */
    public $permission;

    /**
     * Create a new event instance.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Permission $permission
     */
    public function __construct(PermissionContract $permission)
    {
        $this->permission = $permission;
    }
}
