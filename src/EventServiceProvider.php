<?php

namespace CodeMaster\CodeAcl;

use CodeMaster\CodeAcl\Events\Permission\PermissionSaved;
use CodeMaster\CodeAcl\Events\Permission\PermissionUpdated;
use CodeMaster\CodeAcl\Events\Permission\PermissionDeleted;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionSaved as ListenersPermissionSaved;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionUpdated as ListenersPermissionUpdated;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionDeleted as ListenersPermissionDeleted;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PermissionSaved::class => [ ListenersPermissionSaved::class, ],
        PermissionUpdated::class => [ ListenersPermissionUpdated::class, ],
        PermissionDeleted::class => [ ListenersPermissionDeleted::class, ]
    ];
}
