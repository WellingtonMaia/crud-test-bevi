<?php

namespace Modules\Product\ModelFilters;

use EloquentFilter\ModelFilter;

class ProductFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function name(string $name): ProductFilter
    {
        return $this->where('name', 'LIKE', "%$name%");
    }

    public function description(string $description): ProductFilter
    {
        return $this->where('description', 'LIKE', "%$description%");
    }

    public function status(string $status): ProductFilter
    {
        return $this->where('status', '=', $status);
    }
}
