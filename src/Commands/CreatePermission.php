<?php

namespace CodeMaster\CodeAcl\Commands;

use Illuminate\Console\Command;
use CodeMaster\CodeAcl\Contracts\Permission as PermissionContract;

class CreatePermission extends Command
{
    protected $signature = 'code-acl:create-permission
                {name : The name of the permission}';

    protected $description = 'Create a permission';

    public function handle()
    {
        $permissionClass = app(PermissionContract::class);

        $permission = $permissionClass::findOrCreate($this->argument('name'));

        $this->info("Permission `{$permission->name}` created");
    }
}
