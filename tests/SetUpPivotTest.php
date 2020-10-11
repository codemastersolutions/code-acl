<?php

namespace CodeMaster\CodeAcl\Test;

class SetUpPivotTest extends TestCase
{
    /** @test */
    public function it_can_get_default_model_connection_name()
    {
        app('config')->set('code-acl.defaults.connection', null);

        $model = new ModelPivot();

        $model->setUp();

        $this->assertNull($model->getConnectionName());
    }

    /** @test */
    public function it_can_get_default_model_key_name()
    {
        app('config')->set('code-acl.models.permission.primary_key.name', null);

        $model = new ModelPivot();

        $model->setUp();

        $this->assertEquals('id', $model->getKeyName());
    }

    /** @test */
    public function it_can_get_default_model_table_name()
    {
        app('config')->set('code-acl.models.permission.table', null);

        $model = new ModelPivot();

        $model->setUp();

        $this->assertEquals('model_pivot', $model->getTable());
    }

    /** @test */
    public function it_throws_an_exception_when_the_config_not_loaded()
    {
        $this->expectException(\Exception::class);

        app('config')->set('code-acl.models.permission', []);

        $model = new ModelPivot();

        $model->setUp();
    }

}
