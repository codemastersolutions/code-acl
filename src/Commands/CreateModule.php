<?php

namespace CodeMaster\CodeAcl\Commands;

use Illuminate\Console\Command;
use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;

class CreateModule extends Command
{
    protected $signature = 'code-acl:create-module
                {name : The name of the module}';

    protected $description = 'Create a module';

    public function handle()
    {
        $moduleClass = app(ModuleContract::class);

        $module = $moduleClass::findOrCreate($this->argument('name'));

        $this->info("Module `{$module->name}` created");
    }
}
