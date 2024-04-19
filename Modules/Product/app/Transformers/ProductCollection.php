<?php

namespace Modules\Product\Transformers;

use App\Http\Resources\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductCollection extends Collection
{
    public function getCollection(): AnonymousResourceCollection
    {
        return ProductResource::collection($this->collection);
    }
}
