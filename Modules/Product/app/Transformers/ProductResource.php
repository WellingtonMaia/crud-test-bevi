<?php

namespace Modules\Product\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    #[ArrayShape([
        'id' => "mixed",
        'name' => "mixed",
        'description' => "mixed",
        'price' => "mixed"
    ])]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
        ];
    }
}
