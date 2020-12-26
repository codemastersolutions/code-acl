<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

trait DefaultUserSystemMutation
{
    public function args(): array
    {
        $args = [];

        if(method_exists($this, 'baseArgs')) {
            $args = $this->baseArgs();

            if (isset($args['modelIdOrSlug'])) {
                $args['modelIdOrSlug']['description'] = 'Id do usuário';
            }

            if (isset($args['relationIdOrSlug'])) {
                $args['relationIdOrSlug']['description'] = 'Id ou slug do sistema';
            }
        }

        return $args;
    }
}
