<?php

namespace CodeMaster\CodeAcl\Listeners\User;

use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Events\User\UserRetrieved as UserRetrievedEvent;
use CodeMaster\CodeLog\Logging\Log;

class UserRetrieved
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
     * @param  \CodeMaster\CodeAcl\Events\User\UserRetrieved $event
     * @return void
     */
    public function handle(UserRetrievedEvent $event)
    {
        Log::info('user-retrieved', ['users' => $event->user->toArray()]);
    }
}
