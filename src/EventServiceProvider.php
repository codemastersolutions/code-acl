<?php

namespace CodeMaster\CodeAcl;

use CodeMaster\CodeAcl\Events\Module\ModuleSaved;
use CodeMaster\CodeAcl\Events\Module\ModuleUpdated;
use CodeMaster\CodeAcl\Events\Module\ModuleDeleted;
use CodeMaster\CodeAcl\Events\Permission\PermissionSaved;
use CodeMaster\CodeAcl\Events\Permission\PermissionUpdated;
use CodeMaster\CodeAcl\Events\Permission\PermissionDeleted;
use CodeMaster\CodeAcl\Events\Role\RoleSaved;
use CodeMaster\CodeAcl\Events\Role\RoleUpdated;
use CodeMaster\CodeAcl\Events\Role\RoleDeleted;
use CodeMaster\CodeAcl\Events\System\SystemSaved;
use CodeMaster\CodeAcl\Events\System\SystemUpdated;
use CodeMaster\CodeAcl\Events\System\SystemDeleted;
use CodeMaster\CodeAcl\Listeners\Module\ModuleSaved as ListenersModuleSaved;
use CodeMaster\CodeAcl\Listeners\Module\ModuleUpdated as ListenersModuleUpdated;
use CodeMaster\CodeAcl\Listeners\Module\ModuleDeleted as ListenersModuleDeleted;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionSaved as ListenersPermissionSaved;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionUpdated as ListenersPermissionUpdated;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionDeleted as ListenersPermissionDeleted;
use CodeMaster\CodeAcl\Listeners\Role\RoleSaved as ListenersRoleSaved;
use CodeMaster\CodeAcl\Listeners\Role\RoleUpdated as ListenersRoleUpdated;
use CodeMaster\CodeAcl\Listeners\Role\RoleDeleted as ListenersRoleDeleted;
use CodeMaster\CodeAcl\Listeners\System\SystemSaved as ListenersSystemSaved;
use CodeMaster\CodeAcl\Listeners\System\SystemUpdated as ListenersSystemUpdated;
use CodeMaster\CodeAcl\Listeners\System\SystemDeleted as ListenersSystemDeleted;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ModuleSaved::class => [ ListenersModuleSaved::class, ],
        ModuleUpdated::class => [ ListenersModuleUpdated::class, ],
        ModuleDeleted::class => [ ListenersModuleDeleted::class, ],
        PermissionSaved::class => [ ListenersPermissionSaved::class, ],
        PermissionUpdated::class => [ ListenersPermissionUpdated::class, ],
        PermissionDeleted::class => [ ListenersPermissionDeleted::class, ],
        RoleSaved::class => [ ListenersRoleSaved::class, ],
        RoleUpdated::class => [ ListenersRoleUpdated::class, ],
        RoleDeleted::class => [ ListenersRoleDeleted::class, ],
        SystemSaved::class => [ ListenersSystemSaved::class, ],
        SystemUpdated::class => [ ListenersSystemUpdated::class, ],
        SystemDeleted::class => [ ListenersSystemDeleted::class, ],
    ];
}
