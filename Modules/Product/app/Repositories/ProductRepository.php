<?php

namespace Modules\Product\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Product\ModelFilters\ProductFilter;
use Modules\Product\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getPaginate(array $filter = [], int $perPage = 5): LengthAwarePaginator
    {
        return Product::filter($filter, ProductFilter::class)->paginate($perPage);
    }

    public function create(array $data): Product
    {
        $product = new Product();
        $product->fill($data);
        $product->save();

        return $product;
    }

    public function getById(int $id): Product|null
    {
        /**
         * @var Product $product
         */
        $product = Product::query()->find($id);

        if (!empty($product)) {
            return $product;
        }

        return null;
    }

    public function update(array $data, int $id): Product|null
    {
        $product = $this->getById($id);

        if (!empty($product)) {
            $product->fill($data);
            $product->save();
        }

        return $product;
    }

    public function delete(int $id): Product|null
    {
        $product = $this->getById($id);

        if (!empty($product)) {
            $product->delete();
        }

        return $product;
    }
}
