<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\AttachRelationModelsMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUser;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUserRoleMutation;
use Rebing\GraphQL\Support\Mutation;

class GiveRoleToUserMutation extends Mutation
{
    use DefaultUser, DefaultUserRoleMutation, AttachRelationModelsMutation;

    protected $attributes = [
        'name' => 'GiveRoleToUserMutation',
        'description' => 'Vincula um papel ao usuário',
        'modelGiveRelationships' => 'giveRoles',
        'modelCheckRelationship' => 'checkRole',
    ];
}
