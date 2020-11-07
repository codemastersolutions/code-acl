<?php

namespace CodeMaster\CodeAcl\Listeners\System;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use CodeMaster\CodeAcl\Events\System\SystemSaved as SystemSavedEvent;
use CodeMaster\CodeLog\Logging\Log;

class SystemSaved
{
    /** @var \CodeMaster\CodeAcl\Contracts\System $system */
    public SystemContract $system;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SystemContract $system)
    {
        $this->system = $system;
    }

    /**
     * Handle the event.
     *
     * @param  \CodeMaster\CodeAcl\Events\System\SystemSaved $event
     * @return void
     */
    public function handle(SystemSavedEvent $event)
    {
        Log::info('system-saved', ['systems' => $event->system->toArray()]);
    }
}
