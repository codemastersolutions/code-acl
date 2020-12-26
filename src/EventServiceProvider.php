<?php

namespace CodeMaster\CodeAcl;

use CodeMaster\CodeAcl\Events\Module\ModuleCreated;
use CodeMaster\CodeAcl\Events\Module\ModuleDeleted;
use CodeMaster\CodeAcl\Events\Module\ModuleRetrieved;
use CodeMaster\CodeAcl\Events\Module\ModuleSaved;
use CodeMaster\CodeAcl\Events\Module\ModuleUpdated;
use CodeMaster\CodeAcl\Events\Permission\PermissionCreated;
use CodeMaster\CodeAcl\Events\Permission\PermissionDeleted;
use CodeMaster\CodeAcl\Events\Permission\PermissionRetrieved;
use CodeMaster\CodeAcl\Events\Permission\PermissionSaved;
use CodeMaster\CodeAcl\Events\Permission\PermissionUpdated;
use CodeMaster\CodeAcl\Events\Role\RoleCreated;
use CodeMaster\CodeAcl\Events\Role\RoleDeleted;
use CodeMaster\CodeAcl\Events\Role\RoleRetrieved;
use CodeMaster\CodeAcl\Events\Role\RoleSaved;
use CodeMaster\CodeAcl\Events\Role\RoleUpdated;
use CodeMaster\CodeAcl\Events\System\SystemCreated;
use CodeMaster\CodeAcl\Events\System\SystemDeleted;
use CodeMaster\CodeAcl\Events\System\SystemRetrieved;
use CodeMaster\CodeAcl\Events\System\SystemSaved;
use CodeMaster\CodeAcl\Events\System\SystemUpdated;
use CodeMaster\CodeAcl\Events\User\UserCreated;
use CodeMaster\CodeAcl\Events\User\UserDeleted;
use CodeMaster\CodeAcl\Events\User\UserRetrieved;
use CodeMaster\CodeAcl\Events\User\UserSaved;
use CodeMaster\CodeAcl\Events\User\UserUpdated;
use CodeMaster\CodeAcl\Listeners\Module\ModuleCreated as ListenersModuleCreated;
use CodeMaster\CodeAcl\Listeners\Module\ModuleDeleted as ListenersModuleDeleted;
use CodeMaster\CodeAcl\Listeners\Module\ModuleRetrieved as ListenersModuleRetrieved;
use CodeMaster\CodeAcl\Listeners\Module\ModuleSaved as ListenersModuleSaved;
use CodeMaster\CodeAcl\Listeners\Module\ModuleUpdated as ListenersModuleUpdated;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionCreated as ListenersPermissionCreated;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionDeleted as ListenersPermissionDeleted;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionRetrieved as ListenersPermissionRetrieved;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionSaved as ListenersPermissionSaved;
use CodeMaster\CodeAcl\Listeners\Permission\PermissionUpdated as ListenersPermissionUpdated;
use CodeMaster\CodeAcl\Listeners\Role\RoleCreated as ListenersRoleCreated;
use CodeMaster\CodeAcl\Listeners\Role\RoleDeleted as ListenersRoleDeleted;
use CodeMaster\CodeAcl\Listeners\Role\RoleRetrieved as ListenersRoleRetrieved;
use CodeMaster\CodeAcl\Listeners\Role\RoleSaved as ListenersRoleSaved;
use CodeMaster\CodeAcl\Listeners\Role\RoleUpdated as ListenersRoleUpdated;
use CodeMaster\CodeAcl\Listeners\System\SystemCreated as ListenersSystemCreated;
use CodeMaster\CodeAcl\Listeners\System\SystemDeleted as ListenersSystemDeleted;
use CodeMaster\CodeAcl\Listeners\System\SystemRetrieved as ListenersSystemRetrieved;
use CodeMaster\CodeAcl\Listeners\System\SystemSaved as ListenersSystemSaved;
use CodeMaster\CodeAcl\Listeners\System\SystemUpdated as ListenersSystemUpdated;
use CodeMaster\CodeAcl\Listeners\User\UserCreated as ListenersUserCreated;
use CodeMaster\CodeAcl\Listeners\User\UserDeleted as ListenersUserDeleted;
use CodeMaster\CodeAcl\Listeners\User\UserRetrieved as ListenersUserRetrieved;
use CodeMaster\CodeAcl\Listeners\User\UserSaved as ListenersUserSaved;
use CodeMaster\CodeAcl\Listeners\User\UserUpdated as ListenersUserUpdated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ModuleCreated::class => [ ListenersModuleCreated::class, ],
        ModuleDeleted::class => [ ListenersModuleDeleted::class, ],
        ModuleRetrieved::class => [ ListenersModuleRetrieved::class, ],
        ModuleSaved::class => [ ListenersModuleSaved::class, ],
        ModuleUpdated::class => [ ListenersModuleUpdated::class, ],
        PermissionCreated::class => [ ListenersPermissionCreated::class, ],
        PermissionDeleted::class => [ ListenersPermissionDeleted::class, ],
        PermissionRetrieved::class => [ ListenersPermissionRetrieved::class, ],
        PermissionSaved::class => [ ListenersPermissionSaved::class, ],
        PermissionUpdated::class => [ ListenersPermissionUpdated::class, ],
        RoleCreated::class => [ ListenersRoleCreated::class, ],
        RoleDeleted::class => [ ListenersRoleDeleted::class, ],
        RoleRetrieved::class => [ ListenersRoleRetrieved::class, ],
        RoleSaved::class => [ ListenersRoleSaved::class, ],
        RoleUpdated::class => [ ListenersRoleUpdated::class, ],
        SystemCreated::class => [ ListenersSystemCreated::class, ],
        SystemDeleted::class => [ ListenersSystemDeleted::class, ],
        SystemRetrieved::class => [ ListenersSystemRetrieved::class, ],
        SystemSaved::class => [ ListenersSystemSaved::class, ],
        SystemUpdated::class => [ ListenersSystemUpdated::class, ],
        UserCreated::class => [ ListenersUserCreated::class, ],
        UserDeleted::class => [ ListenersUserDeleted::class, ],
        UserRetrieved::class => [ ListenersUserRetrieved::class, ],
        UserSaved::class => [ ListenersUserSaved::class, ],
        UserUpdated::class => [ ListenersUserUpdated::class, ],
    ];
}
