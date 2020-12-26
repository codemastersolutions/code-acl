<?php

namespace CodeMaster\CodeAcl\Listeners\User;

use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Events\User\UserDeleted as UserDeletedEvent;
use CodeMaster\CodeLog\Logging\Log;

class UserDeleted
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
     * @param  \CodeMaster\CodeAcl\Events\User\UserDeleted $event
     * @return void
     */
    public function handle(UserDeletedEvent $event)
    {
        Log::info('user-deleted', ['users' => $event->user->toArray()]);
    }
}
