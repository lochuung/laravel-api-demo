<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductUnitTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    /** @test */
    public function it_can_list_product_units()
    {
        // Arrange
        ProductUnit::factory()->count(3)->create(['product_id' => $this->product->id]);

        // Act
        $response = $this->actingAs($this->user, 'api')
            ->getJson("/api/v1/products/{$this->product->id}/units");

        // Assert
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'product_id',
                        'unit_name',
                        'sku',
                        'barcode',
                        'conversion_rate',
                        'selling_price',
                        'is_base_unit',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_create_a_product_unit()
    {
        // Arrange
        $unitData = [
            'unit_name' => 'box',
            'conversion_rate' => 12.0,
            'selling_price' => 120.00,
            'is_base_unit' => false
        ];

        // Act
        $response = $this->actingAs($this->user, 'api')
            ->postJson("/api/v1/products/{$this->product->id}/units", $unitData);

        // Assert
        $response->assertOk()
            ->assertJsonFragment([
                'unit_name' => 'box',
                'product_id' => $this->product->id,
                'conversion_rate' => 12.0,
                'selling_price' => 120.00,
                'is_base_unit' => false
            ]);

        $this->assertDatabaseHas('product_units', [
            'product_id' => $this->product->id,
            'unit_name' => 'box',
            'conversion_rate' => 12.0,
            'selling_price' => 120.00,
            'is_base_unit' => false
        ]);
    }

    /** @test */
    public function it_can_show_a_specific_product_unit()
    {
        // Arrange
        $unit = ProductUnit::factory()->create(['product_id' => $this->product->id]);

        // Act
        $response = $this->actingAs($this->user, 'api')
            ->getJson("/api/v1/products/{$this->product->id}/units/{$unit->id}");

        // Assert
        $response->assertOk()
            ->assertJsonFragment([
                'id' => $unit->id,
                'product_id' => $this->product->id,
                'unit_name' => $unit->unit_name
            ]);
    }

    /** @test */
    public function it_cannot_show_unit_that_does_not_belong_to_product()
    {
        // Arrange
        $otherProduct = Product::factory()->create();
        $unit = ProductUnit::factory()->create(['product_id' => $otherProduct->id]);

        // Act
        $response = $this->actingAs($this->user, 'api')
            ->getJson("/api/v1/products/{$this->product->id}/units/{$unit->id}");

        // Assert
        $response->assertForbidden();
    }

    /** @test */
    public function it_can_update_a_product_unit()
    {
        // Arrange
        $unit = ProductUnit::factory()->create(['product_id' => $this->product->id]);
        $updateData = [
            'unit_name' => 'updated box',
            'conversion_rate' => 24.0,
            'selling_price' => 240.00,
            'is_base_unit' => true
        ];

        // Act
        $response = $this->actingAs($this->user, 'api')
            ->putJson("/api/v1/products/units/{$unit->id}", $updateData);

        // Assert
        $response->assertOk()
            ->assertJsonFragment([
                'unit_name' => 'updated box',
                'conversion_rate' => 24.0,
                'selling_price' => 240.00,
                'is_base_unit' => true
            ]);

        $this->assertDatabaseHas('product_units', [
            'id' => $unit->id,
            'unit_name' => 'updated box',
            'conversion_rate' => 24.0,
            'selling_price' => 240.00,
            'is_base_unit' => true
        ]);
    }

    /** @test */
    public function it_unsets_other_base_units_when_setting_new_base_unit()
    {
        // Arrange
        $baseUnit = ProductUnit::factory()->create([
            'product_id' => $this->product->id,
            'is_base_unit' => true
        ]);
        $newUnit = ProductUnit::factory()->create([
            'product_id' => $this->product->id,
            'is_base_unit' => false
        ]);

        // Act
        $this->actingAs($this->user, 'api')
            ->putJson("/api/v1/products/units/{$newUnit->id}", [
                'unit_name' => $newUnit->unit_name,
                'conversion_rate' => $newUnit->conversion_rate,
                'selling_price' => $newUnit->selling_price,
                'is_base_unit' => true
            ]);

        // Assert
        $this->assertDatabaseHas('product_units', [
            'id' => $baseUnit->id,
            'is_base_unit' => false
        ]);
        $this->assertDatabaseHas('product_units', [
            'id' => $newUnit->id,
            'is_base_unit' => true
        ]);
    }

    /** @test */
    public function it_can_delete_a_product_unit()
    {
        // Arrange
        $unit = ProductUnit::factory()->create([
            'product_id' => $this->product->id,
            'is_base_unit' => false
        ]);

        // Act
        $response = $this->actingAs($this->user, 'api')
            ->deleteJson("/api/v1/products/units/{$unit->id}");

        // Assert
        $response->assertOk();
        $this->assertDatabaseMissing('product_units', ['id' => $unit->id]);
    }

    /** @test */
    public function it_cannot_delete_base_unit()
    {
        // Arrange
        $baseUnit = ProductUnit::factory()->create([
            'product_id' => $this->product->id,
            'is_base_unit' => true
        ]);

        $this->product->update(['base_unit_id' => $baseUnit->id]);

        // Act
        $response = $this->actingAs($this->user, 'api')
            ->deleteJson("/api/v1/products/units/{$baseUnit->id}");

        // Assert
        $response->assertStatus(400);
        $this->assertDatabaseHas('product_units', ['id' => $baseUnit->id]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        // Act
        $response = $this->actingAs($this->user, 'api')
            ->postJson("/api/v1/products/{$this->product->id}/units", []);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'unit_name',
                'conversion_rate',
                'selling_price',
                'is_base_unit'
            ]);
    }

    /** @test */
    public function it_validates_conversion_rate_minimum()
    {
        // Act
        $response = $this->actingAs($this->user, 'api')
            ->postJson("/api/v1/products/{$this->product->id}/units", [
                'unit_name' => 'test',
                'conversion_rate' => 0,
                'selling_price' => 10,
                'is_base_unit' => false
            ]);

        // Assert
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['conversion_rate']);
    }
}
