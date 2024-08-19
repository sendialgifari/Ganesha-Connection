<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->product_category,
            'name' => $this->name,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'image_thumb' => url('/').$this->image_thumb,
            'image' => url('/').$this->image,
            'fake_price' => $this->fake_price,
            'price' => $this->price,
            'ratings' => $this->ratings,
            'views_counter' => $this->views_counter,
            'is_active_comment' => $this->is_active_comment,
            'slug' => $this->slug,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}