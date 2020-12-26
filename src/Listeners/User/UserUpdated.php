<?php

namespace CodeMaster\CodeAcl\Listeners\User;

use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Events\User\UserUpdated as UserUpdatedEvent;
use CodeMaster\CodeLog\Logging\Log;

class UserUpdated
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
     * @param  \CodeMaster\CodeAcl\Events\User\UserUpdated $event
     * @return void
     */
    public function handle(UserUpdatedEvent $event)
    {
        Log::info('user-updated', ['users' => $event->user->toArray()]);
    }
}
