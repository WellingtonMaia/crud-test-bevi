<?php

namespace Modules\Product\Services;

use Modules\Product\Transformers\ProductCollection;

interface ProductServiceInterface
{
    public function getPaginate(array $filter = [], int $perPage = 5): ProductCollection;
    public function create(array $data): array;
    public function getById(int $id): array;
    public function update(array $data, int $id): array;
    public function delete(int $id): array;
}
