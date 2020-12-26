<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\AttachRelationModelsMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUser;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUserModuleMutation;
use Rebing\GraphQL\Support\Mutation;

class GiveModuleToUserMutation extends Mutation
{
    use DefaultUser, DefaultUserModuleMutation, AttachRelationModelsMutation;

    protected $attributes = [
        'name' => 'GiveModuleToUserMutation',
        'description' => 'Vincula um módulo ao usuário',
        'modelGiveRelationships' => 'giveModules',
        'modelCheckRelationship' => 'checkModule',
    ];
}
