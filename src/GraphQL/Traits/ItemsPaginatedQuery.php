<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Ramsey\Uuid\Uuid;
use Rebing\GraphQL\Support\Facades\GraphQL;

trait ItemsPaginatedQuery
{
    use BaseQuery;

    public function type(): Type
    {
        return GraphQL::paginate(class_basename(\get_class($this->model)));
    }

    public function baseArgs(): array
    {
        return [
            'doesnt_have_relationship' => [
                'type' => Type::string(),
                'description' => 'Nome do relacionamento. Quando este argumento é informado, a consulta de retornará apenas usuários que não possuem relacionamentos. Caso este argumento seja informado, o argumento "relationshipIdOrSlug" é de preenchimento obrigatório, caso contrário, este argumento será ignorado.'
            ],
            'have_relationship' => [
                'type' => Type::string(),
                'description' => 'Nome do relacionamento. Quando este argumento é informado, a consulta de retornará apenas usuários que possuem relacionamentos. Caso este argumento seja informado, o argumento "relationshipIdOrSlug" é de preenchimento obrigatório, caso contrário, este argumento será ignorado.'
            ],
            'order_by' => [
                'type' => Type::string(),
                'description' => 'Campos para ordenação. Caso deseje especificar a direção de ordenação, a mesma deve ser informada junto ao nome do campo separado por espaço. Ex.: "<campo> <direção>(name desc)". Para multiplos campos, eles devem ser separados por vírgula. Ex.: "<campo1>(name), <campo2> <direção>(created_at desc)". Caso este parâmetro não seja informado, a consulta será ordenada pelo campo pesquisado e se for uma consulta genérica, será ordenado de forma crescente seguindo os campos informados na variável "fillable" do model.'
            ],
            'page' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Página a ser consultada.'
            ],
            'per_page' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Máximo de registros por página.'
            ],
            'relationshipIdOrSlug' => [
                'type' => Type::string(),
                'description' => 'ID ou Slug do relacionamento. Esse argumento é obrigatório caso um dos argumentos, "doesnt_have_relationship" ou "have_relationship", seja informado.'
            ],
            'search_term' => [
                'type' => Type::string(),
                'description' => "Campo a ser pesquisado na tabela"
            ],
            'search_field' => [
                'type' => Type::string(),
                'description' => "Termo(Parcial ou Integral) a ser pesquisado na tabela"
            ]
        ];
    }

    public function baseRules(array $args = []): array
    {
        return [
            'doesnt_have_relationship' => ['string'],
            'have_relationship' => ['string'],
            'order_by' => ['string'],
            'page' => ['required', 'integer'],
            'per_page' => ['required', 'integer'],
            'relationshipIdOrSlug' => ['string'],
            'search_term' => ['string'],
            'search_field' => ['string'],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return $this->filterPaginated($args, $getSelectFields);
    }

    public function baseQuery($args, $fields)
    {
        $args['per_page'] = $args['per_page'] === 0 ? 1000 : $args['per_page'];

        $query = $this->model::select($fields->getSelect())->with($fields->getRelations());

        if (isset($args['search_term']) && isset($args['search_field']) && ($args['search_term'] !== '*') && ($args['search_term'] !== '')) {
            $term = str_replace(' ', '%', $args['search_term']);
            $query->where($args['search_field'], 'like', '%' . $term . '%');
        }

        if (isset($args['relationshipIdOrSlug'])) {
            if (isset($args['doesnt_have_relationship']) && method_exists($this->model, $args['doesnt_have_relationship'])) {
                $relationship = $args['doesnt_have_relationship'];

                $query->whereDoesntHave($relationship, function (Builder $queryBuilder) use ($args, $relationship) {
                    $relationId = $this->getRelationship($relationship, $args['relationshipIdOrSlug'], 'id');
                    $queryBuilder->where("{$relationship}.id", '=', $relationId->id);
                });
            }

            if (isset($args['have_relationship']) && method_exists($this->model, $args['have_relationship'])) {
                $relationship = $args['have_relationship'];

                $query->whereHas($relationship, function (Builder $queryBuilder) use ($args, $relationship) {
                    $relationId = $this->getRelationship($relationship, $args['relationshipIdOrSlug'], 'id');
                    $queryBuilder->where("{$relationship}.id", '=', $relationId->id);
                });
            }
        }

        if (isset($args['order_by'])) {
            $orderFields = explode(',', $args['order_by']);

            foreach($orderFields as $field) {
                $fieldArray = explode(' ', $field);
                $query->orderBy($fieldArray[0], $fieldArray[1] ?? 'asc');
            }
        } else if (isset($args['search_field'])) {
            foreach($this->model->getFillable() as $field) {
                $query->orderBy($field, 'asc');
            }
        } else {
            foreach($this->model->getFillable() as $field) {
                $query->orderBy($field, 'asc');
            }
        }

        return $query;
    }

    public function filterPaginated($args, $getSelectFields)
    {
        $fields = $getSelectFields();
        $query = $this->baseQuery($args, $fields);

        return $query->paginate($args['per_page'] ?? $this->model->perPage, ['*'], 'page', $args['page'] ?? 1);
    }

    private function getRelationship($relationshipName, $relationshipIdOrSlug, $fields)
    {
        if (isset($relationshipIdOrSlug)) {
            $modelRelation = $this->model->{$relationshipName}()->getModel();

            if (Uuid::isValid($relationshipIdOrSlug) || is_int($relationshipIdOrSlug)) {
                return $modelRelation::select($fields)->whereId($relationshipIdOrSlug)->first();
            }

            return $modelRelation::select($fields)->whereSlug($relationshipIdOrSlug)->first();
        }

        return null;
    }
}
