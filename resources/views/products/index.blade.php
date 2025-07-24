@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-box"></i> Products Management
            </h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Search products by name or SKU...">
            <button class="btn btn-outline-secondary" type="button">Search</button>
        </div>
    </div>
    <div class="col-md-3">
        <select class="form-select">
            <option selected>All Categories</option>
            <option value="electronics">Electronics</option>
            <option value="clothing">Clothing</option>
            <option value="books">Books</option>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-select">
            <option selected>All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="out_of_stock">Out of Stock</option>
        </select>
    </div>
</div>

<!-- Products Grid -->
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">All Products (567)</h5>
            <div class="btn-group" role="group" aria-label="View toggle">
                <input type="radio" class="btn-check" name="view" id="grid-view" autocomplete="off" checked>
                <label class="btn btn-outline-secondary" for="grid-view">
                    <i class="fas fa-th"></i> Grid
                </label>
                <input type="radio" class="btn-check" name="view" id="list-view" autocomplete="off">
                <label class="btn btn-outline-secondary" for="list-view">
                    <i class="fas fa-list"></i> List
                </label>
            </div>
        </div>
    </div>
</div>

<!-- Grid View -->
<div id="grid-container" class="row">
    <div class="col-md-4 col-lg-3 mb-4">
        <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product Image">
            <div class="card-body d-flex flex-column">
                <h6 class="card-title">Wireless Bluetooth Headphones</h6>
                <p class="card-text text-muted small flex-grow-1">Premium quality wireless headphones with noise cancellation</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-primary fw-bold">$199.99</span>
                    <span class="badge bg-success">In Stock</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">SKU: WBH-001</small>
                    <small class="text-muted">Stock: 25</small>
                </div>
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('products.show', 1) }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('products.edit', 1) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-3 mb-4">
        <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product Image">
            <div class="card-body d-flex flex-column">
                <h6 class="card-title">Smartphone Case</h6>
                <p class="card-text text-muted small flex-grow-1">Durable and stylish smartphone protection</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-primary fw-bold">$29.99</span>
                    <span class="badge bg-warning">Low Stock</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">SKU: SC-002</small>
                    <small class="text-muted">Stock: 3</small>
                </div>
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('products.show', 2) }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('products.edit', 2) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-3 mb-4">
        <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product Image">
            <div class="card-body d-flex flex-column">
                <h6 class="card-title">Gaming Mouse</h6>
                <p class="card-text text-muted small flex-grow-1">High precision gaming mouse with RGB lighting</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-primary fw-bold">$79.99</span>
                    <span class="badge bg-danger">Out of Stock</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">SKU: GM-003</small>
                    <small class="text-muted">Stock: 0</small>
                </div>
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('products.show', 3) }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('products.edit', 3) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-lg-3 mb-4">
        <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Product Image">
            <div class="card-body d-flex flex-column">
                <h6 class="card-title">Laptop Stand</h6>
                <p class="card-text text-muted small flex-grow-1">Adjustable aluminum laptop stand for better ergonomics</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-primary fw-bold">$49.99</span>
                    <span class="badge bg-success">In Stock</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">SKU: LS-004</small>
                    <small class="text-muted">Stock: 15</small>
                </div>
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('products.show', 4) }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('products.edit', 4) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- List View (Hidden by default) -->
<div id="list-container" class="d-none">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <img src="https://via.placeholder.com/50" class="rounded" alt="Product" width="50">
                            </td>
                            <td>
                                <h6 class="mb-0">Wireless Bluetooth Headphones</h6>
                                <small class="text-muted">Premium quality wireless headphones</small>
                            </td>
                            <td>WBH-001</td>
                            <td class="fw-bold text-primary">$199.99</td>
                            <td>25</td>
                            <td><span class="badge bg-success">In Stock</span></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('products.show', 1) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', 1) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">Previous</a>
        </li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
            <a class="page-link" href="#">Next</a>
        </li>
    </ul>
</nav>
@endsection

@section('scripts')
<script>
    // Toggle between grid and list view
    document.getElementById('grid-view').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('grid-container').classList.remove('d-none');
            document.getElementById('list-container').classList.add('d-none');
        }
    });

    document.getElementById('list-view').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('grid-container').classList.add('d-none');
            document.getElementById('list-container').classList.remove('d-none');
        }
    });
</script>
@endsection
