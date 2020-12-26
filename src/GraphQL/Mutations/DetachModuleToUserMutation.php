<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Mutations;

use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUser;
use CodeMaster\CodeAcl\GraphQL\Traits\DefaultUserModuleMutation;
use CodeMaster\CodeAcl\GraphQL\Traits\DetachRelationModelsMutation;
use Rebing\GraphQL\Support\Mutation;

class DetachModuleToUserMutation extends Mutation
{
    use DefaultUser, DefaultUserModuleMutation, DetachRelationModelsMutation;

    protected $attributes = [
        'name' => 'DetachModuleToUserMutation',
        'description' => 'Desvincula um módulo do usuário',
        'modelDetachRelationships' => 'revokeModules',
        'modelCheckRelationship' => 'checkModule',
    ];
}
