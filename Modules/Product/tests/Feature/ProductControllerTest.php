<?php

namespace Modules\Product\tests\Feature;

use JetBrains\PhpStorm\ArrayShape;
use Modules\Product\Database\Factories\ProductFactory;
use Modules\Product\Enum\ProductStatus;
use Modules\Product\Models\Product;
use Symfony\Component\HttpFoundation\Response;
use Tests\Utils\TestCaseHelper;

class ProductControllerTest extends TestCaseHelper
{
    protected array $products = [];
    protected int $countProductCreated = 20;
    protected int $perPage = 5;
    protected string $notFountMessage = "Product not found.";
    protected int $fictitiousId = 1000;

    protected function setUp(): void
    {
        parent::setUp();
        $this->products = Product::factory()->count($this->countProductCreated)->create()->toArray();
    }

    #[ArrayShape([
        'name' => "string",
        'description' => "null|string",
        'price' => "int",
        'status' => "array",
        'stock_quantity' => "int"
    ])]
    private function getProductData(): array
    {
        $factory = new ProductFactory();
        return $factory->definition();
    }

    public function test_it_must_list_products()
    {
        $response = $this->get("/api/products?perPage=$this->perPage");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_it_must_create_product()
    {
        $data = $this->getProductData();
        $response = $this->post("/api/products", $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertHeader('Location');
        $this->assertDatabaseHas('products', $data);
        $this->assertDatabaseCount('products', $this->countProductCreated + 1);
    }

    public function test_it_must_show_product()
    {
        $product = $this->faker->randomElement($this->products);

        $response = $this->get("/api/products/{$product['id']}");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($product['name'], $response->json('name'));
        $this->assertEquals($product['description'], $response->json('description'));
        $this->assertEquals($product['price'], $response->json('price'));
    }

    public function test_it_must_update_product()
    {
        $product = $this->faker->randomElement($this->products);
        $data = $this->getDataToUpdate($product);

        $response = $this->put("/api/products/{$product['id']}", $data);

        /**
         * @var Product $productFound
         */
        $productFound = Product::query()->find($product['id']);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('products', [
            'name' => $data['name'],
            'price' => $data['price'],
        ]);
        $this->assertEquals($data['name'], $productFound->name);
        $this->assertEquals($data['price'], $productFound->price);
    }

    /**
     * @param array $product
     * @return mixed
     */
    public function getDataToUpdate(mixed $product): mixed
    {
        $newName = $this->faker->name;
        $newPrice = $this->faker->numberBetween(100.50, 1000.99);

        $product['name'] = $newName;
        $product['price'] = $newPrice;

        unset($product['id']);
        unset($product['created_at']);
        unset($product['updated_at']);
        return $product;
    }

    public function test_it_must_delete_product()
    {
        $product = $this->faker->randomElement($this->products);

        $response = $this->delete("/api/products/{$product['id']}");

        $productFound = Product::query()->find($product['id']);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseCount('products', 19);
        $this->assertNull($productFound);
    }

    public function test_it_should_not_create_product_because_product_already_exists()
    {
        $product = $this->faker->randomElement($this->products);
        $data = $this->getProductData();
        $data['name'] = $product['name'];

        $response = $this->post("/api/products", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertEquals("Nome já cadastrado.", $response->json('message'));
    }

    public function test_it_should_not_create_product_because_exceeded_character_limit()
    {
        $data = $this->getProductData();
        $data['description'] = $this->faker->text(1000);
        $response = $this->post("/api/products", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertEquals("Limite de caracteres excedido. (máx.: 500).", $response->json('message'));
    }

    public function test_it_should_not_create_product_because_price_less_than_zero()
    {
        $data = $this->getProductData();
        $data['price'] = -2;

        $response = $this->post("/api/products", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertEquals("Preço não pode ser menor que 0.", $response->json('message'));
    }

    public function test_it_should_not_create_product_because_product_status_wrong()
    {
        $data = $this->getProductData();
        $data['status'] = "iv";

        $response = $this->post("/api/products", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertEquals("Informe o status corretamente.", $response->json('message'));
    }

    public function test_it_should_not_create_product_because_stock_quantity_value_is_string()
    {
        $data = $this->getProductData();
        $data['stock_quantity'] = "a";

        $response = $this->post("/api/products", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertEquals("Informe a quantidade de estoque corretamente.", $response->json('message'));
    }

    public function test_it_must_get_product_by_name()
    {
        $name = $this->faker->randomElement($this->products)['name'];

        $response = $this->get("/api/products?perPage=$this->perPage&name=$name");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($name, $response->json('data')[0]['name']);
    }

    public function test_it_must_get_product_by_description()
    {
        $description = $this->faker->text(100);
        $descriptionFilter = substr($description, 0, 15);

        Product::factory()->create(['description' => $description])->toArray();

        $response = $this->get("/api/products?perPage=$this->perPage&description=$descriptionFilter");

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($description, $response->json('data')[0]['description']);
    }

    public function test_it_must_get_product_by_status()
    {
        $perPage = 20;
        $statusInStock = ProductStatus::IN_STOCK->value;
        $productsByStatusLength = collect($this->products)
            ->filter(fn ($item) => $item['status'] === $statusInStock)
            ->count();

        $response = $this->get("/api/products?perPage=$perPage&status=$statusInStock");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount($productsByStatusLength, 'data');
    }

    public function test_it_should_not_show_product_because_id_not_exist()
    {
        $response = $this->get("/api/products/$this->fictitiousId");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals($this->notFountMessage, $response->json('message'));
    }

    public function test_it_should_not_update_product_because_id_not_exist()
    {
        $product = $this->faker->randomElement($this->products);
        $data = $this->getDataToUpdate($product);

        $response = $this->put("/api/products/$this->fictitiousId", $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals($this->notFountMessage, $response->json('message'));
    }

    public function test_it_should_not_delete_product_because_id_not_exist()
    {
        $response = $this->delete("/api/products/$this->fictitiousId");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertEquals($this->notFountMessage, $response->json('message'));
    }
}
