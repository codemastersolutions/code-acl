<?php

namespace CodeMaster\CodeAcl\Listeners\Module;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use CodeMaster\CodeAcl\Events\Module\ModuleCreated as ModuleCreatedEvent;
use CodeMaster\CodeLog\Logging\Log;

class ModuleCreated
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
     * @param  \CodeMaster\CodeAcl\Events\Module\ModuleCreated $event
     * @return void
     */
    public function handle(ModuleCreatedEvent $event)
    {
        Log::info('module-created', ['modules' => $event->module->toArray()]);
    }
}
