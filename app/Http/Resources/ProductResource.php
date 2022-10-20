<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->name,
            'price' => $this->price, //price is in cents. so, divide by 100 to get actual value
            'details' => $this->details,
            'media' => $this->media ? new ProductMediaResource($this->media->first()) : null,
            'is_active' => $this->is_active,
            'is_promotion' => $this->is_promotion,
            'promotion_price' => $this->promotion_price,
            'promotion_start_date' => $this->promotion_start_date,
            'promotion_end_date' => $this->promotion_end_date,
            'categories' => CategoryResource::collection($this->categories),
        ];
    }
}
