<?php

namespace Modules\Product\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Product\Models\Product;

interface ProductRepositoryInterface
{
    public function getPaginate(array $filter = [], int $perPage = 5): LengthAwarePaginator;
    public function create(array $data): Product;
    public function getById(int $id): Product|null;
    public function update(array $data, int $id): Product|null;
    public function delete(int $id): Product|null;
}
