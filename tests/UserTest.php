<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Role;
use CodeMaster\CodeAcl\Contracts\User as UserContract;
use CodeMaster\CodeAcl\Exceptions\UserModelNotFound;
use Illuminate\Database\Eloquent\Collection;

class UserTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_the_user_model_not_found()
    {
        app('config')->set('code-acl.defaults.user', '');

        $this->expectException(UserModelNotFound::class);

        app(UserContract::class);
    }

    /** @test */
    public function it_get_roles_names()
    {
        $rolesNames = $this->testUser->getRolesName();

        $this->assertInstanceOf(Collection::class, $rolesNames);
        $this->assertCount(1, $rolesNames);
    }

    /** @test */
    public function it_get_stored_roles()
    {
        $roles = $this->testUser->getStoredRoles($this->testCreatorRole->id);

        $this->assertInstanceOf(Role::class, $roles);
    }

    /** @test */
    public function it_is_assign_role_to_a_user()
    {
        $hasRoles = $this->testUser1->assignRole($this->testCreatorRole->slug)
                    ->checkRole($this->testCreatorRole->id);

        $this->assertTrue($hasRoles);
    }

    /** @test */
    public function it_is_assign_system_to_a_user()
    {
        $hasSystems = $this->testUser1->assignSystem('New System 1')
                    ->checkSystem('New System 1');

        $this->assertTrue($hasSystems);
    }

    /** @test */
    public function it_is_receive_false_for_non_existing_system_in_has_system_method()
    {
        $this->assertFalse($this->testUser1->hasSystem(''));
    }
}
