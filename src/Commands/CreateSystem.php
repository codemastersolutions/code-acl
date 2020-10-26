<?php

namespace CodeMaster\CodeAcl\Commands;

use Illuminate\Console\Command;
use CodeMaster\CodeAcl\Contracts\System as SystemContract;

class CreateSystem extends Command
{
    protected $signature = 'code-acl:create-system
                {name : The name of the system}';

    protected $description = 'Create a system';

    public function handle()
    {
        $systemClass = app(SystemContract::class);

        $system = $systemClass::findOrCreate($this->argument('name'));

        $this->info("System `{$system->name}` created");
    }
}
