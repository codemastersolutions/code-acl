<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Test\User;
use CodeMaster\CodeAcl\Contracts\Module;
use CodeMaster\CodeAcl\Exceptions\ModuleDoesNotExist;
use Illuminate\Database\Eloquent\Collection;

class HasModulesTest extends TestCase
{
    /** @test */
    public function it_check_if_user_not_have_module()
    {
        $this->assertFalse($this->testUser->hasModule('New Module 1'));
    }

    /** @test */
    public function it_check_if_has_module()
    {
        $this->assertTrue($this->testUser->checkModule($this->testModule->id));
    }

    /** @test */
    public function it_check_if_user_not_has_module()
    {
        $this->assertFalse($this->testUser->checkModule('New Module 1'));
    }

    /** @test */
    public function it_check_if_user_not_has_module_with_isnt_module_exists()
    {
        $this->assertFalse($this->testUser->checkModule('New Module 11'));

        $this->assertFalse($this->testUser->checkModule(''));
    }

    /** @test */
    public function it_throws_an_exception_when_the_module_not_was_stored_in_has_module_method()
    {
        $this->expectException(ModuleDoesNotExist::class);

        $this->testUser->hasModule('non-existing-module');
    }

    /** @test */
    public function it_get_modules_names()
    {
        $modulesNames = $this->testUser->getModulesName();

        $this->assertInstanceOf(Collection::class, $modulesNames);
        $this->assertCount(1, $modulesNames);
    }

    /** @test */
    public function it_can_convert_collection_of_modules_to_module_models()
    {
        $modules = app(Module::class)::all();

        $modulesArray = app(User::class)->convertToModulesModels($modules);

        $this->assertIsArray($modulesArray);
    }
}
