<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\AttachRelationModelsMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultRole;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultRolePermissionMutation;
use Rebing\GraphQL\Support\Mutation;

class GivePermissionToRoleMutation extends Mutation
{
    use DefaultRole, DefaultRolePermissionMutation, AttachRelationModelsMutation;

    protected $attributes = [
        'name' => 'GivePermissionToRoleMutation',
        'description' => 'Vincula uma permissão ao papel',
        'modelGiveRelationships' => 'givePermissions',
        'modelCheckRelationship' => 'checkPermission',
    ];
}
