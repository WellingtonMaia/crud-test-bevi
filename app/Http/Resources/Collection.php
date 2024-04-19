<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class Collection extends ResourceCollection
{
    protected AnonymousResourceCollection $collectionBase;

    abstract public function getCollection(): AnonymousResourceCollection;

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $resourceArray = $this->resource->toArray();

        if (!array_key_exists('per_page', $resourceArray)) {
            return ['data' => $resourceArray];
        }

        return array_merge(['data' => $this->getCollection()], $resourceArray);
    }
}
