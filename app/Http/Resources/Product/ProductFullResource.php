<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\CategorySmallResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductFullResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'published_at' => $this->published_at,
            'categories' => CategorySmallResource::collection(
                $this->whenLoaded('categories')
            ),
        ];
    }
}
