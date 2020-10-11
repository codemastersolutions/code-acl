<?php

namespace CodeMaster\CodeAcl\Commands;

use Illuminate\Console\Command;
use CodeMaster\CodeAcl\CodeAclRegister;

class CacheReset extends Command
{
    protected $signature = 'code-acl:cache-reset';

    protected $description = 'Reset the code acl cache';

    public function handle()
    {
        if (app(CodeAclRegister::class)->forgetCachedPermissions()) {
            $this->info('Code Acl cache flushed.');
        } else {
            $this->error('Unable to flush cache.');
        }
    }
}
