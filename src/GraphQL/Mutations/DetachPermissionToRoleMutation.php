<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultRole;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultRolePermissionMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DetachRelationModelsMutation;
use Rebing\GraphQL\Support\Mutation;

class DetachPermissionToRoleMutation extends Mutation
{
    use DefaultRole, DefaultRolePermissionMutation, DetachRelationModelsMutation;

    protected $attributes = [
        'name' => 'DetachPErmissionToUserMutation',
        'description' => 'Desvincula uma permissão do papel',
        'modelDetachRelationships' => 'revokePermissions',
        'modelCheckRelationship' => 'checkPermission',
    ];
}
