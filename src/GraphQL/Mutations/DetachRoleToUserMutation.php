<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUser;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUserPermissionMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DetachRelationModelsMutation;
use Rebing\GraphQL\Support\Mutation;

class DetachRoleToUserMutation extends Mutation
{
    use DefaultUser, DefaultUserPermissionMutation, DetachRelationModelsMutation;

    protected $attributes = [
        'name' => 'DetachRoleToUserMutation',
        'description' => 'Desvincula um papel do usuário',
        'modelDetachRelationships' => 'revokeRoles',
        'modelCheckRelationship' => 'checkRole',
    ];
}
