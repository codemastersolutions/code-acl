<?php

declare(strict_types=1);

namespace CodeMaster\CodeAcl\GraphQL\Traits;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

trait BaseMutation
{
    protected $model;

    /**
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function store($args): Model
    {
        return $this->model::findOrCreate($args['name']);
    }

    /**
     * @param array $args
     * @return bool;
     */
    public function delete($args): bool
    {
        $data = $this->find($args);

        if ($data instanceof Model) {
            return $data->delete();
        }

        return false;
    }

    /**
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Model|bool;
     */
    public function update($args)
    {
        $data = $this->find($args);

        if ($data instanceof Model) {
            $data->name = $args['name'];
            $data->save();
            $data->refresh();

            return $data;
        }

        return false;
    }

    /**
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Model|array;
     */
    private function find($args)
    {
        if (isset($args['idOrSlug'])) {
            try {
                if (Uuid::isValid($args['idOrSlug'])) {
                    $data = $this->model::findById($args['idOrSlug']);
                } else {
                    $data = $this->model::findBySlug($args['idOrSlug']);
                }

                return $data;
            } catch(\Exception $e) {
            }
        }

        return [];
    }
}
