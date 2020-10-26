<?php

namespace CodeMaster\CodeAcl\Test;

use CodeMaster\CodeAcl\Exceptions\ConfigNotLoaded;
use CodeMaster\CodeAcl\Exceptions\UserModelNotFound;
use CodeMaster\CodeAcl\Http\Controllers\UsersController;

class HelperTest extends TestCase
{
    /** @test */
    public function it_is_get_value_0_when_meta_data_is_empty()
    {
        $per_page = per_page();

        $this->assertEquals(0, $per_page);
    }
}
