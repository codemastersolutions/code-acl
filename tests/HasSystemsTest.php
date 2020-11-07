<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Test\User;
use CodeMaster\CodeAcl\Contracts\System;
use CodeMaster\CodeAcl\Exceptions\SystemDoesNotExist;
use Illuminate\Database\Eloquent\Collection;

class HasSystemsTest extends TestCase
{
    /** @test */
    public function it_check_if_user_not_have_system()
    {
        $this->assertFalse($this->testUser->hasSystem('New System 1'));
    }

    /** @test */
    public function it_check_if_has_system()
    {
        $this->assertTrue($this->testUser->checkSystem($this->testSystem->id));
    }

    /** @test */
    public function it_check_if_user_not_has_system()
    {
        $this->assertFalse($this->testUser->checkSystem('New System 1'));
    }

    /** @test */
    public function it_check_if_user_not_has_system_with_isnt_system_exists()
    {
        $this->assertFalse($this->testUser->checkSystem('New System 11'));
    }

    /** @test */
    public function it_throws_an_exception_when_the_system_not_was_stored_in_has_system_method()
    {
        $this->expectException(SystemDoesNotExist::class);

        $this->testUser->hasSystem('non-existing-system');
    }

    /** @test */
    public function it_get_systems_names()
    {
        $systemsNames = $this->testUser->getSystemsName();

        $this->assertInstanceOf(Collection::class, $systemsNames);
        $this->assertCount(1, $systemsNames);
    }

    /** @test */
    public function it_can_convert_collection_of_systems_to_system_models()
    {
        $systems = app(System::class)::all();

        $systemsArray = app(User::class)->convertToSystemsModels($systems);

        $this->assertIsArray($systemsArray);
    }
}
