<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

trait DefaultRolePermissionMutation
{
    public function args(): array
    {
        $args = [];

        if(method_exists($this, 'baseArgs')) {
            $args = $this->baseArgs();

            if (isset($args['modelIdOrSlug'])) {
                $args['modelIdOrSlug']['description'] = 'Id ou slug do papel';
            }

            if (isset($args['relationIdOrSlug'])) {
                $args['relationIdOrSlug']['description'] = 'Id ou slug da permissão';
            }
        }

        return $args;
    }
}
