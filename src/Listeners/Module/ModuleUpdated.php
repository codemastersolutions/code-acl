<?php

namespace CodeMaster\CodeAcl\Listeners\Module;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use CodeMaster\CodeAcl\Events\Module\ModuleUpdated as ModuleUpdatedEvent;
use CodeMaster\CodeLog\Logging\Log;

class ModuleUpdated
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
     * @param  \CodeMaster\CodeAcl\Events\Module\ModuleUpdated $event
     * @return void
     */
    public function handle(ModuleUpdatedEvent $event)
    {
        Log::info('module-updated', ['modules' => $event->module->toArray()]);
    }
}
