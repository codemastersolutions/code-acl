<?php

namespace CodeMaster\CodeAcl\Listeners\System;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use CodeMaster\CodeAcl\Events\System\SystemUpdated as SystemUpdatedEvent;
use CodeMaster\CodeLog\Logging\Log;

class SystemUpdated
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
     * @param  \CodeMaster\CodeAcl\Events\System\SystemUpdated $event
     * @return void
     */
    public function handle(SystemUpdatedEvent $event)
    {
        Log::info('system-updated', ['systems' => $event->system->toArray()]);
    }
}
