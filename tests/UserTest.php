<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Exceptions\UserModelNotFound;

class UserTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_the_user_model_not_found()
    {
        app('config')->set('code-acl.defaults.user', '');

        $this->expectException(UserModelNotFound::class);

        app(UserContract::class);

    }
}
