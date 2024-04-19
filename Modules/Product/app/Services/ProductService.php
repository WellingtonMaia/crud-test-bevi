<?php

namespace Modules\Product\Services;

use JetBrains\PhpStorm\ArrayShape;
use Modules\Product\Repositories\ProductRepositoryInterface;
use Modules\Product\Transformers\ProductCollection;
use Modules\Product\Transformers\ProductResource;
use Symfony\Component\HttpFoundation\Response;

class ProductService implements ProductServiceInterface
{
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getPaginate(array $filter = [], int $perPage = 5): ProductCollection
    {
        return new ProductCollection($this->productRepository->getPaginate($filter, $perPage));
    }

    #[ArrayShape([
        'message' => "string",
        'code' => "int",
        'headers' => "string[]"]
    )] public function create(array $data): array
    {
        $product = $this->productRepository->create($data);

        return [
            'message' => ['message' => 'product created with success!'],
            'code' => Response::HTTP_CREATED,
            'headers' => ['Location' => config('app.url')."/api/products/$product->id"]
        ];

    }

    public function getById(int $id): array
    {
        $product = $this->productRepository->getById($id);

        if (!empty($product)) {
            return ['message' => new ProductResource($product), 'code' => Response::HTTP_OK];
        }

        return $this->getMessageNotFound();
    }

    #[ArrayShape([
        'message' => "string",
        'code' => "int"
    ])]
    public function update(array $data, int $id): array
    {
        $product = $this->productRepository->update($data, $id);

        if (!empty($product)) {
            return ['message' => ['message' => 'Product saved with success'], 'code' => Response::HTTP_OK];
        }

        return $this->getMessageNotFound();
    }

    #[ArrayShape([
        'message' => "string",
        'code' => "int"
    ])]
    public function delete(int $id): array
    {
        $product = $this->productRepository->delete($id);

        if (!empty($product)) {
            return ['message' => ['message' => 'Product deleted with success'], 'code' => Response::HTTP_OK];
        }

        return $this->getMessageNotFound();
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'message' => "string",
        'code' => "int"
    ])]
    private function getMessageNotFound(): array
    {
        return ['message' => ['message' => 'Product not found.'], 'code' => Response::HTTP_NOT_FOUND];
    }
}
