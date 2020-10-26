<?php

namespace CodeMaster\CodeAcl\Test;


class UsersRouteTest extends TestCase
{
    private string $urlUsers, $accept, $pathPermissions, $pathRoles, $pathModules, $pathSystems;

    public function setUp(): void
    {
        parent::setUp();

        $urlPath = config('code-acl.defaults.code-acl.path');
        $this->urlUsers = "api/{$urlPath}/users/";
        $this->accept = "application/json";
        $this->pathPermissions = "/permissions";
        $this->pathRoles = "/roles";
        $this->pathSystems = "/systems";
        $this->pathModules = "/modules";
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_systems_data()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathSystems}",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_modules_data()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathModules}",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_roles_data()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathRoles}",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_modules_data()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathModules}",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_systems_data()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathSystems}",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_roles_data()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathRoles}",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_give_modules_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathModules}",
            [
                'modules' => [
                    'new-module',
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(201);
        $response->assertSuccessful();
        $this->assertTrue($response['result']);
    }

    /** @test */
    public function it_is_give_systems_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathSystems}",
            [
                'systems' => [
                    'new-system',
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(201);
        $response->assertSuccessful();
        $this->assertTrue($response['result']);
    }

    /** @test */
    public function it_is_give_permissions_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathPermissions}",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(201);
        $response->assertSuccessful();
        $this->assertTrue($response['result']);
    }

    /** @test */
    public function it_is_revoke_modules_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathModules}",
            [
                'modules' => [
                    'new-module',
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_revoke_systems_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathSystems}",
            [
                'systems' => [
                    'new-system',
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_revoke_permissions_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathPermissions}",
            [
                'permissions' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(204);
        $response->assertSuccessful();
        $response->assertNoContent();
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_modules_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathModules}",
            [
                'modules' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_Systems_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathSystems}",
            [
                'systems' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_permissions_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathPermissions}",
            [
                'permissions' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_modules_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathModules}",
            [
                'modules' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_systems_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathSystems}",
            [
                'systems' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_permissions_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathPermissions}",
            [
                'permissions' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_permissions_data()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathPermissions}",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_permissions_data()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathPermissions}",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(422);
    }

    /** @test */
    public function it_is_give_roles_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathRoles}",
            [
                'roles' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => $this->accept]
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
            "{$this->urlUsers}{$this->testUser->id}{$this->pathRoles}",
            [
                'roles' => [
                    'role-1',
                    'role-2'
                ]
            ],
            ['Accept' => $this->accept]
        );

        $response->assertStatus(204);
        $response->assertNoContent();
        $response->assertSuccessful();
    }

    /** @test */
    public function it_is_get_failure_with_give_non_exists_roles_to_a_user()
    {
        $response = $this->post(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathRoles}",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_get_failure_with_revoke_non_exists_roles_to_a_user()
    {
        $response = $this->delete(
            "{$this->urlUsers}{$this->testUser->id}{$this->pathRoles}",
            [
                'roles' => [
                    'insert',
                    'list'
                ]
            ],
            ['Accept' => $this->accept]
        );
        $response->assertStatus(422);
        $this->assertFalse($response['result']);
    }

    /** @test */
    public function it_is_can_get_modules_attached_to_a_user()
    {
        $response = $this->get(
            "{$this->urlUsers}{$this->testUser1->id}{$this->pathModules}",
            ['Accept' => $this->accept]
        );

        $response->assertStatus(200);
        $response->assertSuccessful();
    }

    /** @test */
    public function it_is_can_get_systems_attached_to_a_user()
    {
        $response = $this->get(
            "{$this->urlUsers}{$this->testUser1->id}{$this->pathSystems}",
            ['Accept' => $this->accept]
        );

        $response->assertStatus(200);
        $response->assertSuccessful();
    }

    /** @test */
    public function it_is_can_get_permissions_attached_to_a_user()
    {
        $response = $this->get(
            "{$this->urlUsers}{$this->testUser1->id}{$this->pathPermissions}",
            ['Accept' => $this->accept]
        );

        $response->assertStatus(200);
        $response->assertSuccessful();
    }

    /** @test */
    public function it_is_can_get_roles_attached_to_a_user()
    {
        $response = $this->get(
            "{$this->urlUsers}{$this->testUser1->id}{$this->pathRoles}",
            ['Accept' => $this->accept]
        );

        $response->assertStatus(200);
        $response->assertSuccessful();
    }
}
