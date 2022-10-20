<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->image ? $this->image : null,
            'parent_id' => $this->parent_id,
            'level' => $this->level,
            'is_active' => $this->is_active,
            'sub_categories' => CategoryResource::collection($this->subCategories),
        ];
    }
}
