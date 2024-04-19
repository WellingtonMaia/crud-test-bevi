<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;

interface ProductControllerInterface
{
    public function index(Request $request): JsonResponse;
    public function store(StoreProductRequest $request): JsonResponse;
    public function show(int $id): JsonResponse;
    public function update(UpdateProductRequest $request, int $id): JsonResponse;
    public function destroy(int $id): JsonResponse;
}
