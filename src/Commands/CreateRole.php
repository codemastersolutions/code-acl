<?php

namespace CodeMaster\CodeAcl\Commands;

use Illuminate\Console\Command;
use CodeMaster\CodeAcl\Contracts\Role as RoleContract;

class CreateRole extends Command
{
    protected $signature = 'code-acl:create-role
                {name : The name of the role}';

    protected $description = 'Create a role';

    public function handle()
    {
        $roleClass = app(RoleContract::class);

        $role = $roleClass::findOrCreate($this->argument('name'));

        $this->info("Role `{$role->name}` created");
    }
}
