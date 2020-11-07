<?php

namespace CodeMaster\CodeAcl\Listeners\System;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use CodeMaster\CodeAcl\Events\System\SystemCreated as SystemCreatedEvent;
use CodeMaster\CodeLog\Logging\Log;

class SystemCreated
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
     * @param \CodeMaster\CodeAcl\Events\System\SystemCreated $event
     * @return void
     */
    public function handle(SystemCreatedEvent $event)
    {
        Log::info('system-created', ['systems' => $event->system->toArray()]);
    }
}
