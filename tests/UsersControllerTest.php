<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use CodeMaster\CodeAcl\Exceptions\UserModelNotFound;
use CodeMaster\CodeAcl\Http\Controllers\UsersController;

class UsersControllerTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_the_user_model_not_found()
    {
        app('config')->set('code-acl.defaults.user', '');

        $this->expectException(UserModelNotFound::class);

        app(UsersController::class);

    }

    /** @test */
    public function it_throws_an_exception_when_the_config_not_found()
    {
        app('config')->set('code-acl.models.user_has_role.meta_data', '');

        $this->expectException(ConfigNotLoaded::class);

        app(UsersController::class);

    }
}
