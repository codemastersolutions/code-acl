<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\AttachRelationModelsMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUser;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUserPermissionMutation;
use Rebing\GraphQL\Support\Mutation;

class GivePermissionToUserMutation extends Mutation
{
    use DefaultUser, DefaultUserPermissionMutation, AttachRelationModelsMutation;

    protected $attributes = [
        'name' => 'AddPermissionToUserMutation',
        'description' => 'Vincula uma permissão ao usuário',
        'modelGiveRelationships' => 'givePermissions',
        'modelCheckRelationship' => 'checkPermission',
    ];
}
