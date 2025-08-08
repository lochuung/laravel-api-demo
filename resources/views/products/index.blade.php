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
        <div class="col-md-4">
            <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
                <input type="text" class="form-control" placeholder="Search products by name, code, or description...">
                <button class="btn btn-outline-secondary" type="button">Search</button>
            </div>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="category-filter">
                <option value="all" selected>All Categories</option>
                <!-- Categories will be populated dynamically -->
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="status-filter">
                <option value="all" selected>All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="out_of_stock">Out of Stock</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" id="code-prefix-filter">
                <option value="all" selected>All Prefixes</option>
                <!-- Code prefixes will be populated dynamically -->
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" id="price-filter-btn" data-bs-toggle="modal"
                    data-bs-target="#priceFilterModal">
                <i class="fas fa-dollar-sign"></i> Price Range
            </button>
        </div>
    </div>

    <!-- Advanced Filters Row -->
    <div class="row mb-3" id="active-filters" style="display: none;">
        <div class="col-12">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <small class="text-muted">Active filters:</small>
                <div id="filter-tags"></div>
                <button class="btn btn-link btn-sm p-0 ms-2" id="clear-all-filters">
                    <i class="fas fa-times"></i> Clear all
                </button>
            </div>
        </div>
    </div>

    <!-- Price Filter Modal -->
    <div class="modal fade" id="priceFilterModal" tabindex="-1" aria-labelledby="priceFilterModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="priceFilterModalLabel">Price Range Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="min-price" class="form-label">Min Price</label>
                            <input type="number" class="form-control" id="min-price" placeholder="0">
                        </div>
                        <div class="col-md-6">
                            <label for="max-price" class="form-label">Max Price</label>
                            <input type="number" class="form-control" id="max-price" placeholder="1000">
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted" id="price-range-info">Price range: $0 - $1000</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-outline-warning" id="clear-price-filter">Clear</button>
                    <button type="button" class="btn btn-primary" id="apply-price-filter">Apply Filter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 product-count">All Products (0)</h5>
                <div class="d-flex align-items-center gap-3">
                    <!-- Sort Controls -->
                    <div class="d-flex align-items-center gap-2">
                        <small class="text-muted">Sort by:</small>
                        <select class="form-select form-select-sm" id="sort-by" style="width: auto;">
                            <option value="created_at">Date Created</option>
                            <option value="name">Name</option>
                            <option value="price">Price</option>
                            <option value="stock">Stock</option>
                            <option value="updated_at">Last Updated</option>
                        </select>
                        <button class="btn btn-outline-secondary btn-sm" id="sort-order" data-order="desc"
                                title="Sort Direction">
                            <i class="fas fa-sort-amount-down"></i>
                        </button>
                    </div>
                    <!-- View Toggle -->
                    <div class="btn-group" role="group" aria-label="View toggle">
                        <input type="radio" class="btn-check" name="view" id="grid-view" value="grid" autocomplete="off"
                               checked>
                        <label class="btn btn-outline-secondary btn-sm" for="grid-view">
                            <i class="fas fa-th"></i> Grid
                        </label>
                        <input type="radio" class="btn-check" name="view" id="list-view" value="list"
                               autocomplete="off">
                        <label class="btn btn-outline-secondary btn-sm" for="list-view">
                            <i class="fas fa-list"></i> List
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid View -->
    <div id="grid-container" class="row">
        <!-- Products will be populated dynamically -->
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                <h5>Loading products...</h5>
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
                            <th style="width: 80px;">Image</th>
                            <th class="sortable" data-sort="name" style="cursor: pointer;">
                                Product <i class="fas fa-sort text-muted"></i>
                            </th>
                            <th class="sortable" data-sort="code" style="cursor: pointer; width: 120px;">
                                Code <i class="fas fa-sort text-muted"></i>
                            </th>
                            <th class="sortable" data-sort="price" style="cursor: pointer; width: 100px;">
                                Price <i class="fas fa-sort text-muted"></i>
                            </th>
                            <th class="sortable" data-sort="stock" style="cursor: pointer; width: 80px;">
                                Stock <i class="fas fa-sort text-muted"></i>
                            </th>
                            <th style="width: 100px;">Status</th>
                            <th class="sortable" data-sort="created_at" style="cursor: pointer; width: 120px;">
                                Created <i class="fas fa-sort text-muted"></i>
                            </th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Products will be populated dynamically -->
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
                                <div>Loading products...</div>
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
        <ul id="pagination" class="pagination justify-content-center">
            <!-- Pagination will be populated dynamically -->
        </ul>
    </nav>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('js/views/products/index.js') }}"></script>
@endpush
