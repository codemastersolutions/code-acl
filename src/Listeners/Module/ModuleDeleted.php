<?php

namespace CodeMaster\CodeAcl\Listeners\Module;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use CodeMaster\CodeAcl\Events\Module\ModuleDeleted as ModuleDeletedEvent;
use CodeMaster\CodeLog\Logging\Log;

class ModuleDeleted
{
    /** @var \CodeMaster\CodeAcl\Contracts\Module $module */
    public ModuleContract $module;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ModuleContract $module)
    {
        $this->module = $module;
    }

    /**
     * Handle the event.
     *
     * @param  \CodeMaster\CodeAcl\Events\Module\ModuleDeleted $event
     * @return void
     */
    public function handle(ModuleDeletedEvent $event)
    {
        Log::info('module-deleted', ['modules' => $event->module->toArray()]);
    }
}
