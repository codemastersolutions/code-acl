<?php

namespace CodeMaster\CodeAcl\Listeners\User;

use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Events\User\UserSaved as UserSavedEvent;
use CodeMaster\CodeLog\Logging\Log;

class UserSaved
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
     * @param  \CodeMaster\CodeAcl\Events\User\UserSaved $event
     * @return void
     */
    public function handle(UserSavedEvent $event)
    {
        Log::info('user-saved', ['users' => $event->user->toArray()]);
    }
}
