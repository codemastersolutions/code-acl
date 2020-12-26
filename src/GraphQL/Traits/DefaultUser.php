<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use CodeMaster\CodeAcl\Contracts\User;

trait DefaultUser
{
    public function __construct()
    {
        $this->model = app(User::class);
    }

    public function addPermission($args)
    {
        if (isset($args['userId']) && isset($args['permissionIdOrSlug'])) {
            try {
                $user = $this->model::find($args['userId']);

                return $user->givePermissions($args['permissionIdOrSlug'])
                    ->checkPermission($args['permissionIdOrSlug']);
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    public function addRole($args)
    {
        if (isset($args['userId']) && isset($args['roleIdOrSlug'])) {
            try {
                $user = $this->model::find($args['userId']);

                if ($user instanceof User) {
                    return $user->giveRoles($args['roleIdOrSlug'])
                        ->checkRole($args['roleIdOrSlug']);
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }
}
