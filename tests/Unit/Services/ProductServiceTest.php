<?php

namespace Tests\Unit\Services;

use App\Exceptions\BadRequestException;
use App\Helpers\CodeGenerator;
use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;
    private ProductRepositoryInterface $mockRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockRepository = Mockery::mock(ProductRepositoryInterface::class);
        $this->productService = new ProductService($this->mockRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // =================== getAllProducts() Tests ===================

    public function test_get_all_products_success(): void
    {
        // Arrange
        Gate::shouldReceive('denies')
            ->with('viewAny', Product::class)
            ->once()
            ->andReturn(false);

        $filters = ['per_page' => 15];
        $products = new LengthAwarePaginator([], 0, 15);

        $this->mockRepository
            ->shouldReceive('searchAndFilter')
            ->with($filters, 15)
            ->once()
            ->andReturn($products);

        // Act
        $result = $this->productService->getAllProducts($filters);

        // Assert
        $this->assertInstanceOf(ProductCollection::class, $result);
    }

    public function test_get_all_products_throws_authorization_exception(): void
    {
        // Arrange
        Gate::shouldReceive('denies')
            ->with('viewAny', Product::class)
            ->once()
            ->andReturn(true);

        // Assert
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage(__('exception.unauthorized'));

        // Act
        $this->productService->getAllProducts();
    }

    public function test_get_all_products_with_filters(): void
    {
        // Arrange
        Gate::shouldReceive('denies')
            ->with('viewAny', Product::class)
            ->once()
            ->andReturn(false);

        $filters = [
            'search' => 'laptop',
            'category_id' => 1,
            'is_active' => true,
            'per_page' => 20
        ];
        
        $products = new LengthAwarePaginator([], 0, 20);

        $this->mockRepository
            ->shouldReceive('searchAndFilter')
            ->with($filters, 20)
            ->once()
            ->andReturn($products);

        // Act
        $result = $this->productService->getAllProducts($filters);

        // Assert
        $this->assertInstanceOf(ProductCollection::class, $result);
    }

    public function test_get_all_products_with_pagination(): void
    {
        // Arrange
        Gate::shouldReceive('denies')
            ->with('viewAny', Product::class)
            ->once()
            ->andReturn(false);

        $filters = [];
        $products = new LengthAwarePaginator([], 0, 10);

        $this->mockRepository
            ->shouldReceive('searchAndFilter')
            ->with($filters, 10)
            ->once()
            ->andReturn($products);

        // Act
        $result = $this->productService->getAllProducts($filters);

        // Assert
        $this->assertInstanceOf(ProductCollection::class, $result);
    }

    // =================== getProductById() Tests ===================

    public function test_get_product_by_id_success(): void
    {
        // Arrange
        $productId = 1;
        $product = Product::factory()->make(['id' => $productId]);

        $this->mockRepository
            ->shouldReceive('findWithCategory')
            ->with($productId)
            ->once()
            ->andReturn($product);

        Gate::shouldReceive('denies')
            ->with('view', $product)
            ->once()
            ->andReturn(false);

        // Act
        $result = $this->productService->getProductById($productId);

        // Assert
        $this->assertInstanceOf(ProductResource::class, $result);
    }

    public function test_get_product_by_id_not_found(): void
    {
        // Arrange
        $productId = 999;

        $this->mockRepository
            ->shouldReceive('findWithCategory')
            ->with($productId)
            ->once()
            ->andReturn(null);

        // Assert
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage(__('exception.not_found', ['name' => 'product']));
        $this->expectExceptionCode(404);

        // Act
        $this->productService->getProductById($productId);
    }

    public function test_get_product_by_id_authorization_denied(): void
    {
        // Arrange
        $productId = 1;
        $product = Product::factory()->make(['id' => $productId]);

        $this->mockRepository
            ->shouldReceive('findWithCategory')
            ->with($productId)
            ->once()
            ->andReturn($product);

        Gate::shouldReceive('denies')
            ->with('view', $product)
            ->once()
            ->andReturn(true);

        // Assert
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage(__('exception.unauthorized'));

        // Act
        $this->productService->getProductById($productId);
    }

    // =================== createProduct() Tests ===================

    public function test_create_product_success(): void
    {
        // Arrange
        Gate::shouldReceive('denies')
            ->with('create', Product::class)
            ->once()
            ->andReturn(false);

        $inputData = [
            'name' => 'Test Product',
            'price' => 100.00,
            'stock' => 50,
            'category_id' => 1,
            'code_prefix' => 'TST'
        ];

        $expectedData = [
            'name' => 'Test Product',
            'price' => 100.00,
            'stock' => 50,
            'category_id' => 1,
            'code_prefix' => 'TST',
            'code' => 'TST-001',
            'barcode' => 'TST-001'
        ];

        $createdProduct = Product::factory()->make($expectedData);

        // Mock CodeGenerator static method
        $this->mock('alias:' . CodeGenerator::class, function (MockInterface $mock) {
            $mock->shouldReceive('for')
                ->with('TST')
                ->once()
                ->andReturn('TST-001');
        });

        $this->mockRepository
            ->shouldReceive('create')
            ->with($expectedData)
            ->once()
            ->andReturn($createdProduct);

        // Act
        $result = $this->productService->createProduct($inputData);

        // Assert
        $this->assertInstanceOf(ProductResource::class, $result);
    }

    public function test_create_product_with_custom_prefix(): void
    {
        // Arrange
        Gate::shouldReceive('denies')
            ->with('create', Product::class)
            ->once()
            ->andReturn(false);

        $inputData = [
            'name' => 'Custom Product',
            'price' => 200.00,
            'stock' => 25,
            'category_id' => 2,
            'code_prefix' => 'CUSTOM'
        ];

        $expectedData = [
            'name' => 'Custom Product',
            'price' => 200.00,
            'stock' => 25,
            'category_id' => 2,
            'code_prefix' => 'CUSTOM',
            'code' => 'CUSTOM-001',
            'barcode' => 'CUSTOM-001'
        ];

        $createdProduct = Product::factory()->make($expectedData);

        // Mock CodeGenerator static method
        $this->mock('alias:' . CodeGenerator::class, function (MockInterface $mock) {
            $mock->shouldReceive('for')
                ->with('CUSTOM')
                ->once()
                ->andReturn('CUSTOM-001');
        });

        $this->mockRepository
            ->shouldReceive('create')
            ->with($expectedData)
            ->once()
            ->andReturn($createdProduct);

        // Act
        $result = $this->productService->createProduct($inputData);

        // Assert
        $this->assertInstanceOf(ProductResource::class, $result);
    }

    public function test_create_product_with_barcode_fallback(): void
    {
        // Arrange
        Gate::shouldReceive('denies')
            ->with('create', Product::class)
            ->once()
            ->andReturn(false);

        $inputData = [
            'name' => 'Product Without Barcode',
            'price' => 150.00,
            'stock' => 30,
            'category_id' => 1
        ];

        $expectedData = [
            'name' => 'Product Without Barcode',
            'price' => 150.00,
            'stock' => 30,
            'category_id' => 1,
            'code' => 'PRD-001',
            'barcode' => 'PRD-001'
        ];

        $createdProduct = Product::factory()->make($expectedData);

        // Mock CodeGenerator static method
        $this->mock('alias:' . CodeGenerator::class, function (MockInterface $mock) {
            $mock->shouldReceive('for')
                ->with('PRD')
                ->once()
                ->andReturn('PRD-001');
        });

        $this->mockRepository
            ->shouldReceive('create')
            ->with($expectedData)
            ->once()
            ->andReturn($createdProduct);

        // Act
        $result = $this->productService->createProduct($inputData);

        // Assert
        $this->assertInstanceOf(ProductResource::class, $result);
    }

    public function test_create_product_authorization_denied(): void
    {
        // Arrange
        Gate::shouldReceive('denies')
            ->with('create', Product::class)
            ->once()
            ->andReturn(true);

        $inputData = [
            'name' => 'Test Product',
            'price' => 100.00,
            'stock' => 50,
            'category_id' => 1
        ];

        // Assert
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage(__('exception.unauthorized'));

        // Act
        $this->productService->createProduct($inputData);
    }

    // =================== updateProduct() Tests ===================

    public function test_update_product_success(): void
    {
        // Arrange
        $productId = 1;
        $existingProduct = Product::factory()->make([
            'id' => $productId,
            'code' => 'PRD-001'
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'price' => 200.00,
            'stock' => 75
        ];

        $expectedData = [
            'name' => 'Updated Product',
            'price' => 200.00,
            'stock' => 75,
            'code' => 'PRD-001'
        ];

        $updatedProduct = Product::factory()->make($expectedData);

        $this->mockRepository
            ->shouldReceive('find')
            ->with($productId)
            ->once()
            ->andReturn($existingProduct);

        Gate::shouldReceive('denies')
            ->with('update', $existingProduct)
            ->once()
            ->andReturn(false);

        $this->mockRepository
            ->shouldReceive('update')
            ->with($productId, $expectedData)
            ->once()
            ->andReturn($updatedProduct);

        // Act
        $result = $this->productService->updateProduct($productId, $updateData);

        // Assert
        $this->assertInstanceOf(ProductResource::class, $result);
    }

    public function test_update_product_not_found(): void
    {
        // Arrange
        $productId = 999;
        $updateData = ['name' => 'Updated Product'];

        $this->mockRepository
            ->shouldReceive('find')
            ->with($productId)
            ->once()
            ->andReturn(null);

        // Assert
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage(__('exception.not_found', ['name' => 'product']));
        $this->expectExceptionCode(404);

        // Act
        $this->productService->updateProduct($productId, $updateData);
    }

    public function test_update_product_code_preservation(): void
    {
        // Arrange
        $productId = 1;
        $existingProduct = Product::factory()->make([
            'id' => $productId,
            'code' => 'OLD-001'
        ]);

        $updateData = [
            'name' => 'Updated Product',
            'code_prefix' => 'NEW'
        ];

        $expectedData = [
            'name' => 'Updated Product',
            'code_prefix' => 'NEW',
            'code' => 'NEW-001'
        ];

        $updatedProduct = Product::factory()->make($expectedData);

        $this->mockRepository
            ->shouldReceive('find')
            ->with($productId)
            ->once()
            ->andReturn($existingProduct);

        Gate::shouldReceive('denies')
            ->with('update', $existingProduct)
            ->once()
            ->andReturn(false);

        // Mock CodeGenerator static method
        $this->mock('alias:' . CodeGenerator::class, function (MockInterface $mock) {
            $mock->shouldReceive('for')
                ->with('NEW')
                ->once()
                ->andReturn('NEW-001');
        });

        $this->mockRepository
            ->shouldReceive('update')
            ->with($productId, $expectedData)
            ->once()
            ->andReturn($updatedProduct);

        // Act
        $result = $this->productService->updateProduct($productId, $updateData);

        // Assert
        $this->assertInstanceOf(ProductResource::class, $result);
    }

    public function test_update_product_authorization_denied(): void
    {
        // Arrange
        $productId = 1;
        $existingProduct = Product::factory()->make(['id' => $productId]);
        $updateData = ['name' => 'Updated Product'];

        $this->mockRepository
            ->shouldReceive('find')
            ->with($productId)
            ->once()
            ->andReturn($existingProduct);

        Gate::shouldReceive('denies')
            ->with('update', $existingProduct)
            ->once()
            ->andReturn(true);

        // Assert
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage(__('exception.unauthorized'));

        // Act
        $this->productService->updateProduct($productId, $updateData);
    }

    // =================== deleteById() Tests ===================

    public function test_delete_product_success(): void
    {
        // Arrange
        $productId = 1;
        $product = Product::factory()->make(['id' => $productId]);

        $this->mockRepository
            ->shouldReceive('find')
            ->with($productId)
            ->once()
            ->andReturn($product);

        Gate::shouldReceive('denies')
            ->with('delete', $product)
            ->once()
            ->andReturn(false);

        $this->mockRepository
            ->shouldReceive('delete')
            ->with($productId)
            ->once()
            ->andReturn(true);

        // Act & Assert
        $this->productService->deleteById($productId);
        
        // If we reach here without exception, the test passes
        $this->assertTrue(true);
    }

    public function test_delete_product_not_found(): void
    {
        // Arrange
        $productId = 999;

        $this->mockRepository
            ->shouldReceive('find')
            ->with($productId)
            ->once()
            ->andReturn(null);

        // Assert
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage(__('exception.not_found', ['name' => 'product']));
        $this->expectExceptionCode(404);

        // Act
        $this->productService->deleteById($productId);
    }

    public function test_delete_product_authorization_denied(): void
    {
        // Arrange
        $productId = 1;
        $product = Product::factory()->make(['id' => $productId]);

        $this->mockRepository
            ->shouldReceive('find')
            ->with($productId)
            ->once()
            ->andReturn($product);

        Gate::shouldReceive('denies')
            ->with('delete', $product)
            ->once()
            ->andReturn(true);

        // Assert
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage(__('exception.unauthorized'));

        // Act
        $this->productService->deleteById($productId);
    }

    // =================== Additional Method Tests ===================

    public function test_get_filter_options_success(): void
    {
        // Arrange
        Gate::shouldReceive('denies')
            ->with('viewAny', Product::class)
            ->once()
            ->andReturn(false);

        $filterOptions = [
            'categories' => ['Electronics', 'Books'],
            'statuses' => ['active', 'inactive']
        ];

        $this->mockRepository
            ->shouldReceive('getFilterOptions')
            ->once()
            ->andReturn($filterOptions);

        // Act
        $result = $this->productService->getFilterOptions();

        // Assert
        $this->assertEquals($filterOptions, $result);
    }

    public function test_get_filter_options_authorization_denied(): void
    {
        // Arrange
        Gate::shouldReceive('denies')
            ->with('viewAny', Product::class)
            ->once()
            ->andReturn(true);

        // Assert
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage(__('exception.unauthorized'));

        // Act
        $this->productService->getFilterOptions();
    }

    public function test_get_featured_products(): void
    {
        // Arrange
        $featuredProducts = new Collection([
            Product::factory()->make(['is_featured' => true]),
            Product::factory()->make(['is_featured' => true])
        ]);

        $this->mockRepository
            ->shouldReceive('getFeaturedProducts')
            ->once()
            ->andReturn($featuredProducts);

        // Act
        $result = $this->productService->getFeaturedProducts();

        // Assert
        $this->assertIsArray($result);
    }

    public function test_get_low_stock_products(): void
    {
        // Arrange
        $threshold = 5;
        $lowStockProducts = new Collection([
            Product::factory()->make(['stock' => 3]),
            Product::factory()->make(['stock' => 1])
        ]);

        $this->mockRepository
            ->shouldReceive('getLowStockProducts')
            ->with($threshold)
            ->once()
            ->andReturn($lowStockProducts);

        // Act
        $result = $this->productService->getLowStockProducts($threshold);

        // Assert
        $this->assertIsArray($result);
    }

    public function test_get_expiring_soon_products(): void
    {
        // Arrange
        $days = 15;
        $expiringProducts = new Collection([
            Product::factory()->make(['expiry_date' => now()->addDays(10)]),
            Product::factory()->make(['expiry_date' => now()->addDays(5)])
        ]);

        $this->mockRepository
            ->shouldReceive('getExpiringSoonProducts')
            ->with($days)
            ->once()
            ->andReturn($expiringProducts);

        // Act
        $result = $this->productService->getExpiringSoonProducts($days);

        // Assert
        $this->assertIsArray($result);
    }
}
