<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\AttachRelationModelsMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUser;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUserSystemMutation;
use Rebing\GraphQL\Support\Mutation;

class GiveSystemToUserMutation extends Mutation
{
    use DefaultUser, DefaultUserSystemMutation, AttachRelationModelsMutation;

    protected $attributes = [
        'name' => 'GiveSystemToUserMutation',
        'description' => 'Vincula um sistema ao usuário',
        'modelGiveRelationships' => 'giveSystems',
        'modelCheckRelationship' => 'checkSystem',
    ];
}
