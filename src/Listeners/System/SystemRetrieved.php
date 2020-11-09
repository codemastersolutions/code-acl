<?php

namespace CodeMaster\CodeAcl\Listeners\System;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use CodeMaster\CodeAcl\Events\System\SystemRetrieved as SystemRetrievedEvent;
use CodeMaster\CodeLog\Logging\Log;

class SystemRetrieved
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
     * @param  \CodeMaster\CodeAcl\Events\System\SystemRetrieved $event
     * @return void
     */
    public function handle(SystemRetrievedEvent $event)
    {
        Log::info('system-retrieved', ['systems' => $event->system->toArray()]);
    }
}
