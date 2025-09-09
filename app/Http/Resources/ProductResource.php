<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'purchasing_unit' => $this->whenLoaded('purchasingUnit'),
            'selling_unit' => $this->whenLoaded('sellingUnit'),
            'purchasing_price' => $this->purchasing_price,
            'selling_price' => $this->selling_price
        ];
    }
}
