<?php

namespace CodeMaster\CodeAcl\Listeners\Module;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use CodeMaster\CodeAcl\Events\Module\ModuleRetrieved as ModuleRetrievedEvent;
use CodeMaster\CodeLog\Logging\Log;

class ModuleRetrieved
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
     * @param  \CodeMaster\CodeAcl\Events\Module\ModuleRetrieved $event
     * @return void
     */
    public function handle(ModuleRetrievedEvent $event)
    {
        Log::info('module-retrieved', ['modules' => $event->module->toArray()]);
    }
}
