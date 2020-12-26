<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

trait AttachRelationModelsMutation
{
    use BaseRelationModelsMutation;

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (isset($args['modelIdOrSlug']) && isset($args['relationIdOrSlug'])) {
            try {
                if (Uuid::isValid($args['modelIdOrSlug']) || is_int($args['modelIdOrSlug'])) {
                    $model = $this->model::find($args['modelIdOrSlug']);
                } else {
                    $model = $this->model::findBySlug($args['modelIdOrSlug']);
                }

                if ($model instanceof Model) {
                    return $model->{$this->attributes['modelGiveRelationships']}($args['relationIdOrSlug'])
                        ->{$this->attributes['modelCheckRelationship']}($args['relationIdOrSlug']);
                }
            } catch(\Exception $e) {
                return false;
            }
        }

        return true;
    }
}
