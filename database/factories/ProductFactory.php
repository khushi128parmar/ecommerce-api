<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [

            'category_id' => 1,

            'brand_id' => 1,

            'name' => fake()->name(),

            'slug' => Str::slug(fake()->name()),

            'description' => fake()->sentence(),

            'price' => fake()->numberBetween(1000, 5000),

            'stock' => fake()->numberBetween(1, 50),

            'sku' => fake()->unique()->numerify('SKU###'),

            'status' => true,
        ];
    }
}