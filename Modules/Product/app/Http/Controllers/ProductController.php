<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Services\ProductServiceInterface;

class ProductController extends Controller implements ProductControllerInterface
{
    private ProductServiceInterface $productService;

    /**
     * @param ProductServiceInterface $productService
     */
    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->productService->getPaginate(
                $request->all() ?? [], $request->get('perPage') ?? 5
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->only($this->getKeys());

        $response = $this->productService->create($data);

        return response()->json($response['message'], $response['code'], $response['headers']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return  JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $response = $this->productService->getById($id);

        return response()->json($response['message'], $response['code']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $data = $request->all($this->getKeys());

        $response = $this->productService->update($data, $id);

        return response()->json($response['message'], $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $response = $this->productService->delete($id);

        return response()->json($response['message'], $response['code']);
    }

    /**
     * @return string[]
     */
    public function getKeys(): array
    {
        return [
            'name', 'description', 'price', 'status', 'stock_quantity'
        ];
    }
}
