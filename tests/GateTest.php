<?php

namespace CodeMaster\CodeAcl\Test;

use Illuminate\Contracts\Auth\Access\Gate;

class GateTest extends TestCase
{
    /** @test */
    public function it_can_determine_if_a_user_does_not_have_a_permission()
    {
        $this->assertFalse($this->testUser->can('insert-news'));
    }

    /** @test */
    public function it_allows_other_gate_before_callbacks_to_run_if_a_user_does_not_have_a_permission()
    {
        $this->assertFalse($this->testUser->can('insert-news'));

        app(Gate::class)->before(function () {
            return true;
        });

        $this->assertTrue($this->testUser->can('insert-news'));
    }
}
