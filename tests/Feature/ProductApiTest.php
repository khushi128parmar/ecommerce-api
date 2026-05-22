<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

   public function test_products_list_api()
{
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $category = Category::create([

        'name' => 'Electronics',

        'slug' => 'electronics',

        'status' => true
    ]);

    $brand = Brand::create([

        'name' => 'Apple',

        'slug' => 'apple',

        'status' => true
    ]);

    Product::create([

        'category_id' => $category->id,

        'brand_id' => $brand->id,

        'name' => 'iPhone',

        'slug' => 'iphone',

        'description' => 'Test Product',

        'price' => 1000,

        'stock' => 10,

        'sku' => 'SKU001',

        'status' => true
    ]);

    $response = $this->getJson(
        '/api/admin/products'
    );

    $response->assertStatus(200);
}

    public function test_authenticated_user_can_create_product()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $category = Category::create([

            'name' => 'Electronics',

            'slug' => 'electronics',

            'status' => true
        ]);

        $brand = Brand::create([

            'name' => 'Apple',

            'slug' => 'apple',

            'status' => true
        ]);

        $response = $this->postJson(
            '/api/admin/products',
            [

                'category_id' => $category->id,

                'brand_id' => $brand->id,

                'name' => 'iPhone 15',

                'slug' => 'iphone-15',

                'description' => 'Test product',

                'price' => 90000,

                'stock' => 10,

                'sku' => 'SKU001',

                'status' => true
            ]
        );

        $response->assertStatus(200);
    }
}
