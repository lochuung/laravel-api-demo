<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations and seeders
        Passport::loadKeysFrom(storage_path('oauth'));
    }

    public function test_can_list_products(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create(['role' => 'Admin']);
        Passport::actingAs($user);

        // Create a category and product
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'code',
                        'price',
                        'stock',
                        'category_id'
                    ]
                ],
                'meta',
                'links'
            ]);
    }

    public function test_can_create_product(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create(['role' => 'Admin']);
        Passport::actingAs($user);

        // Create a category
        $category = Category::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock' => 100,
            'category_id' => $category->id,
            'is_active' => true,
            'is_featured' => false,
        ];

        $response = $this->postJson('/api/v1/products', $productData);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Test Product']);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'code' => 'PRD0001'
        ]);
    }

    public function test_can_show_product(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create(['role' => 'User']);
        Passport::actingAs($user);

        // Create a category and product
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->getJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $product->id]);
    }

    public function test_can_update_product(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create(['role' => 'Admin']);
        Passport::actingAs($user);

        // Create a category and product
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $updateData = [
            'name' => 'Updated Product Name',
            'code' => $product->code, // Keep the same code
            'price' => 199.99,
            'stock' => 50,
            'category_id' => $category->id,
        ];

        $response = $this->putJson("/api/v1/products/{$product->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Product Name']);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name'
        ]);
    }

    public function test_can_delete_product(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create(['role' => 'Admin']);
        Passport::actingAs($user);

        // Create a category and product
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id
        ]);
    }

    public function test_unauthorized_user_cannot_create_product(): void
    {
        // Create a regular user
        $user = User::factory()->create(['role' => 'User']);
        Passport::actingAs($user);

        $category = Category::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'code' => 'TEST001',
            'price' => 99.99,
            'stock' => 100,
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/api/v1/products', $productData);

        $response->assertStatus(403); // Forbidden
    }
}
