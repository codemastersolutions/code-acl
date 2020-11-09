<?php

namespace CodeMaster\CodeAcl\Events\System;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SystemRetrieved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \CodeMaster\CodeAcl\Contracts\System
     */
    public $system;

    /**
     * Create a new event instance.
     *
     * @param \CodeMaster\CodeAcl\Contracts\System $system
     */
    public function __construct(SystemContract $system)
    {
        $this->system = $system;
    }
}
