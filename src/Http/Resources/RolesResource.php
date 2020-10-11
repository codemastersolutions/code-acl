<?php

namespace CodeMaster\CodeAcl\Http\Resources;

use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class RolesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => is_int($this->id) ? (int) $this->id : (string) $this->id,
            'name' => (string) $this->name,
            'createdAt' => new DateTime($this->created_at),
            'updatedAt' => new DateTime($this->updated_at),
            'slug' => (string) $this->slug,
        ];
    }
}
