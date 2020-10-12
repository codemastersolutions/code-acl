<?php

namespace CodeMaster\CodeAcl\Test;


class RouteTest extends TestCase
{
    private string $urlUsers;

    public function setUp(): void
    {
        parent::setUp();

        $urlPath = config('code-acl.defaults.code-acl.path');
        $this->urlUsers = "api/{$urlPath}/users/";
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_roles_data()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_roles_data()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_give_permissions_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(201);
        $response->assertSuccessful();
        $this->assertTrue($response['result']);
    }

    /** @test */
    public function it_is_revoke_permissions_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_permissions_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'permissions' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_permissions_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'permissions' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_permissions_data()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_permissions_data()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/permissions",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_give_roles_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'roles' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(201);
        $response->assertCreated();
        $response->assertSuccessful();
        $this->assertTrue($response['result']);
    }

    /** @test */
    public function it_is_revoke_roles_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'roles' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(204);
        $response->assertNoContent();
        $response->assertSuccessful();
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_roles_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_roles_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}/roles",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => 'application/json']
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_can_get_permissions_attached_to_a_user()
    {
        $response = $this->get(
            "{$this->urlUsers}{$this->testUser1->id}/permissions",
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertSuccessful();
    }

    /** @test */
    public function it_is_can_get_roles_attached_to_a_user()
    {
        $response = $this->get(
            "{$this->urlUsers}{$this->testUser1->id}/roles",
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $response->assertSuccessful();
    }
}
