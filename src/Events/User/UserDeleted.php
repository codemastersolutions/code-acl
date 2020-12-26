<?php

namespace CodeMaster\CodeAcl\Events\User;

use CodeMaster\CodeAcl\Contracts\User as UserContract;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \CodeMaster\CodeAcl\Contracts\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param \CodeMaster\CodeAcl\Contracts\User $user
     */
    public function __construct(UserContract $user)
    {
        $this->user = $user;
    }
}
