<?php

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Product\Enum\ProductStatus;
use Modules\Product\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    #[ArrayShape([
        'name' => "string",
        'description' => "null|string",
        'price' => "int",
        'status' => "array",
        'stock_quantity' => "int"
    ])]
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->boolean ?
                $this->faker->realTextBetween(80, 300) :
                null,
            'price' => $this->faker->numberBetween(1.00, 1000.99),
            'status' => $this->faker->randomElement([
                ProductStatus::IN_STOCK->value,
                ProductStatus::IN_REPLACEMENT->value,
                ProductStatus::LACKING->value
            ]),
            'stock_quantity' => $this->faker->numberBetween(0, 10000),
        ];
    }
}

