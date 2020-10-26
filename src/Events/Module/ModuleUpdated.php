<?php

namespace CodeMaster\CodeAcl\Events\Module;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ModuleUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \CodeMaster\CodeAcl\Contracts\Module
     */
    public $module;

    /**
     * Create a new event instance.
     *
     * @param \CodeMaster\CodeAcl\Contracts\Module $module
     */
    public function __construct(ModuleContract $module)
    {
        $this->module = $module;
    }
}
