<?php

namespace CodeMaster\CodeAcl\Listeners\System;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use CodeMaster\CodeAcl\Events\System\SystemDeleted as SystemDeletedEvent;
use CodeMaster\CodeLog\Logging\Log;

class SystemDeleted
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
     * @param  \CodeMaster\CodeAcl\Events\System\SystemDeleted $event
     * @return void
     */
    public function handle(SystemDeletedEvent $event)
    {
        Log::info('system-deleted', ['systems' => $event->system->toArray()]);
    }
}
