<?php

namespace CodeMaster\CodeAcl\Test;

class CodeAclServiceProviderTest extends TestCase
{
    /** @test */
    public function it_is_check_register_macro_helper_when_isnt_models_exists()
    {
        $provider = app(Provider::class);

        $this->assertNull($provider->macroHelpers());
    }

    /** @test */
    public function it_is_check_register_model_bind_when_isnt_models_exists()
    {
        $provider = app(Provider::class);
        app('config')->set('code-acl.models', null);

        $this->assertNull($provider->modelBinds());
    }
}
