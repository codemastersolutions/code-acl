<?php

namespace CodeMaster\CodeAcl\Listeners\User;

use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Events\User\UserCreated as UserCreatedEvent;
use CodeMaster\CodeLog\Logging\Log;

class UserCreated
{
    /** @var \CodeMaster\CodeAcl\Contracts\User $user */
    public UserContract $user;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserContract $user)
    {
        $this->user = $user;
    }

    /**
     * Handle the event.
     *
     * @param \CodeMaster\CodeAcl\Events\User\UserCreated $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        Log::info('user-created', ['users' => $event->user->toArray()]);
    }
}
