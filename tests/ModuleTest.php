<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\Module as ModuleContract;
use CodeMaster\CodeAcl\Exceptions\ModuleAlreadyExists;
use CodeMaster\CodeAcl\Exceptions\ModuleDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\ModuleException;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

class ModuleTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_the_module_already_exists()
    {
        $this->expectException(ModuleAlreadyExists::class);

        app(ModuleContract::class)->create(['name' => 'test module']);
        app(ModuleContract::class)->create(['name' => 'test module']);
    }

    /** @test */
    public function it_throws_an_exception_when_the_module_not_exists_in_find_by_id()
    {
        $this->expectException(ModuleDoesNotExist::class);

        app(ModuleContract::class)->findById(Uuid::uuid4());
    }

    /** @test */
    public function it_throws_an_exception_when_the_module_not_exists_in_find_by_name()
    {
        $this->expectException(ModuleDoesNotExist::class);

        app(ModuleContract::class)->findByName('name not exists');
    }

    /** @test */
    public function it_throws_an_exception_when_the_module_not_exists_in_find_by_slug()
    {
        $this->expectException(ModuleDoesNotExist::class);

        app(ModuleContract::class)->findBySlug('slug-not-exists');
    }

    /** @test */
    public function it_is_retrievable_by_id()
    {
        $module = app(ModuleContract::class)->findById($this->testModule->id);

        $this->assertEquals($this->testModule->id, $module->id);
        $this->assertEquals($this->testModule->name, $module->name);
        $this->assertEquals($this->testModule->slug, $module->slug);
    }

    /** @test */
    public function it_is_retrievable_by_name()
    {
        $module = app(ModuleContract::class)->findByName($this->testModule->name);

        $this->assertEquals($this->testModule->id, $module->id);
        $this->assertEquals($this->testModule->name, $module->name);
        $this->assertEquals($this->testModule->slug, $module->slug);
    }

    /** @test */
    public function it_is_retrievable_by_slug()
    {
        $module = app(ModuleContract::class)->findBySlug($this->testModule->slug);

        $this->assertEquals($this->testModule->id, $module->id);
        $this->assertEquals($this->testModule->name, $module->name);
        $this->assertEquals($this->testModule->slug, $module->slug);
    }

    /** @test */
    public function it_is_retrievable_and_created()
    {
        $moduleName = 'Find or Created';
        $module = app(ModuleContract::class)->findOrCreate($moduleName);

        $this->assertEquals($moduleName, $module->name);
    }

    /** @test */
    public function it_is_retrievable_created()
    {
        $moduleName = 'New Module';
        $module = app(ModuleContract::class)->findOrCreate($moduleName);

        $this->assertEquals($moduleName, $module->name);
    }

    /** @test */
    public function it_is_update_module()
    {
        $moduleName = 'test module';
        $module = app(ModuleContract::class)->findOrCreate($moduleName);

        $this->assertEquals($moduleName, $module->name);
        $this->assertInstanceOf(ModuleContract::class, $module);

        $module->update(['name' => 'test module edited']);
        $module->refresh();

        $this->assertEquals('test module edited', $module->name);
        $this->assertInstanceOf(ModuleContract::class, $module);
    }

    /** @test */
    public function it_is_delete_module()
    {
        $moduleName = 'Delete Module';
        $module = app(ModuleContract::class)->findOrCreate($moduleName);

        $this->assertEquals($moduleName, $module->name);
        $this->assertInstanceOf(ModuleContract::class, $module);

        $this->assertTrue($module->delete());
    }

    /** @test */
    public function it_throws_an_exception_when_occurred_error_on_create_module()
    {
        app('config')->set('code-acl.models.module.primary_key.name', 'other_id');

        $class = app(ModuleContract::class);

        $this->expectException(ModuleException::class);

        $class->create(['name' => 'other-test-module']);
    }

    /** @test */
    public function it_is_get_modules_names()
    {
        $class = app(ModuleContract::class);

        $names = $class->getStoredNames();

        $this->assertInstanceOf(Collection::class, $names);
    }
}
