<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Contracts\System as SystemContract;
use CodeMaster\CodeAcl\Exceptions\SystemAlreadyExists;
use CodeMaster\CodeAcl\Exceptions\SystemDoesNotExist;
use CodeMaster\CodeAcl\Exceptions\SystemException;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

class SystemTest extends TestCase
{
    /** @test */
    public function it_throws_an_exception_when_the_system_already_exists()
    {
        $this->expectException(SystemAlreadyExists::class);

        app(SystemContract::class)->create(['name' => 'test system']);
        app(SystemContract::class)->create(['name' => 'test system']);
    }

    /** @test */
    public function it_throws_an_exception_when_the_system_not_exists_in_find_by_id()
    {
        $this->expectException(SystemDoesNotExist::class);

        app(SystemContract::class)->findById(Uuid::uuid4());
    }

    /** @test */
    public function it_throws_an_exception_when_the_system_not_exists_in_find_by_name()
    {
        $this->expectException(SystemDoesNotExist::class);

        app(SystemContract::class)->findByName('name not exists');
    }

    /** @test */
    public function it_throws_an_exception_when_the_system_not_exists_in_find_by_slug()
    {
        $this->expectException(SystemDoesNotExist::class);

        app(SystemContract::class)->findBySlug('slug-not-exists');
    }

    /** @test */
    public function it_is_retrievable_by_id()
    {
        $system = app(SystemContract::class)->findById($this->testSystem->id);

        $this->assertEquals($this->testSystem->id, $system->id);
        $this->assertEquals($this->testSystem->name, $system->name);
        $this->assertEquals($this->testSystem->slug, $system->slug);
    }

    /** @test */
    public function it_is_retrievable_by_name()
    {
        $system = app(SystemContract::class)->findByName($this->testSystem->name);

        $this->assertEquals($this->testSystem->id, $system->id);
        $this->assertEquals($this->testSystem->name, $system->name);
        $this->assertEquals($this->testSystem->slug, $system->slug);
    }

    /** @test */
    public function it_is_retrievable_by_slug()
    {
        $system = app(SystemContract::class)->findBySlug($this->testSystem->slug);

        $this->assertEquals($this->testSystem->id, $system->id);
        $this->assertEquals($this->testSystem->name, $system->name);
        $this->assertEquals($this->testSystem->slug, $system->slug);
    }

    /** @test */
    public function it_is_retrievable_and_created()
    {
        $systemName = 'Find or Created';
        $system = app(SystemContract::class)->findOrCreate($systemName);

        $this->assertEquals($systemName, $system->name);
    }

    /** @test */
    public function it_is_retrievable_created()
    {
        $systemName = 'New System';
        $system = app(SystemContract::class)->findOrCreate($systemName);

        $this->assertEquals($systemName, $system->name);
    }

    /** @test */
    public function it_is_update_system()
    {
        $systemName = 'test system';
        $system = app(SystemContract::class)->findOrCreate($systemName);

        $this->assertEquals($systemName, $system->name);
        $this->assertInstanceOf(SystemContract::class, $system);

        $system->update(['name' => 'test system edited']);
        $system->refresh();

        $this->assertEquals('test system edited', $system->name);
        $this->assertInstanceOf(SystemContract::class, $system);
    }

    /** @test */
    public function it_is_delete_system()
    {
        $systemName = 'Delete system';
        $system = app(SystemContract::class)->findOrCreate($systemName);

        $this->assertEquals($systemName, $system->name);
        $this->assertInstanceOf(SystemContract::class, $system);

        $this->assertTrue($system->delete());
    }

    /** @test */
    public function it_throws_an_exception_when_occurred_error_on_create_system()
    {
        app('config')->set('code-acl.models.system.primary_key.name', 'other_id');

        $class = app(SystemContract::class);

        $this->expectException(SystemException::class);

        $class->create(['name' => 'other-test-system']);
    }

    /** @test */
    public function it_is_get_systems_names()
    {
        $class = app(SystemContract::class);

        $names = $class->getStoredNames();

        $this->assertInstanceOf(Collection::class, $names);
    }
}
