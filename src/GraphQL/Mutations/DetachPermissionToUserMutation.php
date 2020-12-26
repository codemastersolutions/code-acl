<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUser;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUserPermissionMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DetachRelationModelsMutation;
use Rebing\GraphQL\Support\Mutation;

class DetachPermissionToUserMutation extends Mutation
{
    use DefaultUser, DefaultUserPermissionMutation, DetachRelationModelsMutation;

    protected $attributes = [
        'name' => 'DetachPermissionToUserMutation',
        'description' => 'Desvincula uma permissão do usuário',
        'modelDetachRelationships' => 'revokePermissions',
        'modelCheckRelationship' => 'checkPermission',
    ];
}
