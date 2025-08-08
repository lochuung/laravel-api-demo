@extends('layouts.app')

@section('title', 'Product Details')

@push('styles')
    <link rel="stylesheet" href="{{ asset('/css/products/show.css') }}">
@endpush

@push('scripts')
    <script type="module" src="{{ asset('/js/views/products/show.js') }}"></script>
    <script>
        window.productId = {{ $id ?? 'null' }};
    </script>
@endpush

@section('content')
    <!-- Loading Overlay -->
    <div id="loading-overlay"
         class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center d-none"
         style="background-color: rgba(255, 255, 255, 0.8); z-index: 9999;">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="mt-3">
                <h5 class="text-primary">Loading Product Details...</h5>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-box"></i> Product Details
                </h1>
                <div>
                    <a href="#" id="edit-product-link" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button id="delete-product-btn" class="btn btn-danger me-2">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Images -->
        <div class="col-md-5">
            <div class="card stats-card">
                <div class="card-body">
                    <!-- Main Image -->
                    <div class="text-center mb-3 product-image-container">
                        <img id="main-image" src="https://via.placeholder.com/400x300"
                             class="img-fluid rounded" alt="Product Image" style="max-height: 400px;">
                    </div>
                </div>
            </div>

            <!-- Product Stats -->
            <div class="card mt-3 stats-card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-primary" id="stat-stock">0</h5>
                            <small class="text-muted">In Stock</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-success" id="stat-margin">0%</h5>
                            <small class="text-muted">Margin</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <span class="badge" id="stat-status">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-md-7">
            <div class="card stats-card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab"
                                    data-bs-target="#details"
                                    type="button" role="tab" aria-controls="details" aria-selected="true">
                                <i class="fas fa-info-circle"></i> Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="units-tab" data-bs-toggle="tab" data-bs-target="#units"
                                    type="button" role="tab" aria-controls="units" aria-selected="false">
                                <i class="fas fa-layer-group"></i> Units
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory"
                                    type="button" role="tab" aria-controls="inventory" aria-selected="false">
                                <i class="fas fa-boxes"></i> Inventory
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales"
                                    type="button" role="tab" aria-controls="sales" aria-selected="false">
                                <i class="fas fa-chart-line"></i> Sales
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="productTabsContent">
                        <!-- Product Details Tab -->
                        <div class="tab-pane fade show active" id="details" role="tabpanel"
                             aria-labelledby="details-tab">
                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-tag"></i> Name:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <h5 class="mb-0" id="product-name">Loading...</h5>
                                </div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-code"></i> SKU:</strong>
                                </div>
                                <div class="col-sm-9" id="product-sku">Loading...</div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-barcode"></i> Barcode:</strong>
                                </div>
                                <div class="col-sm-9" id="product-barcode">Loading...</div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-ruler"></i> Base Unit:</strong>
                                </div>
                                <div class="col-sm-9" id="product-base-unit">Loading...</div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-layer-group"></i> Category:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-primary" id="product-category">Loading...</span>
                                </div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-dollar-sign"></i> Price:</strong>
                                </div>
                                <div class="col-sm-9 price-display" id="product-price">
                                    <h4 class="text-primary mb-0">Loading...</h4>
                                    <small class="text-muted">Loading...</small>
                                </div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-money-bill"></i> Cost:</strong>
                                </div>
                                <div class="col-sm-9" id="product-cost">Loading...</div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-percentage"></i> Margin:</strong>
                                </div>
                                <div class="col-sm-9" id="product-margin">Loading...</div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-toggle-on"></i> Status:</strong>
                                </div>
                                <div class="col-sm-9" id="product-status">
                                    Loading...
                                </div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-align-left"></i> Description:</strong>
                                </div>
                                <div class="col-sm-9" id="product-description">
                                    Loading...
                                </div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-boxes"></i> Stock:</strong>
                                </div>
                                <div class="col-sm-9" id="product-stock">Loading...</div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-exclamation-triangle"></i> Min Stock:</strong>
                                </div>
                                <div class="col-sm-9" id="product-min-stock">Loading...</div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-chart-bar"></i> Stock Status:</strong>
                                </div>
                                <div class="col-sm-9" id="product-stock-status">Loading...</div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-calendar-times"></i> Expiry:</strong>
                                </div>
                                <div class="col-sm-9" id="product-expiry">Loading...</div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-calendar"></i> Created:</strong>
                                </div>
                                <div class="col-sm-9" id="product-created">Loading...</div>
                            </div>

                            <div class="row mb-3 product-detail-row">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-calendar-edit"></i> Updated:</strong>
                                </div>
                                <div class="col-sm-9" id="product-updated">Loading...</div>
                            </div>
                        </div>

                        <!-- Product Units Tab -->
                        <div class="tab-pane fade" id="units" role="tabpanel" aria-labelledby="units-tab">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="mb-0">
                                    <i class="fas fa-layer-group"></i> Product Units
                                </h6>
                                <button class="btn btn-primary btn-sm" id="add-unit-btn">
                                    <i class="fas fa-plus"></i> New Unit
                                </button>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-info text-white inventory-stat-card">
                                        <div class="card-body text-center">
                                            <h4 class="mb-0" id="total-units">0</h4>
                                            <small>Total Units</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-success text-white inventory-stat-card">
                                        <div class="card-body text-center">
                                            <h4 class="mb-0" id="base-unit-name">-</h4>
                                            <small>Base Unit</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-warning text-white inventory-stat-card">
                                        <div class="card-body text-center">
                                            <h4 class="mb-0" id="total-unit-variations">0</h4>
                                            <small>Variations</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive units-table-container">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-hashtag"></i> ID</th>
                                        <th><i class="fas fa-tag"></i> Unit Name</th>
                                        <th><i class="fas fa-code"></i> SKU</th>
                                        <th><i class="fas fa-barcode"></i> Barcode</th>
                                        <th><i class="fas fa-exchange-alt"></i> Conversion</th>
                                        <th><i class="fas fa-dollar-sign"></i> Price</th>
                                        <th><i class="fas fa-boxes"></i> Stock</th>
                                        <th><i class="fas fa-star"></i> Base</th>
                                        <th><i class="fas fa-cogs"></i> Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="units-table-body">
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="fas fa-spinner fa-spin"></i> Loading units...
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Inventory Tab -->
                        <!-- Inventory Tab -->
                        <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-primary text-white inventory-stat-card">
                                        <div class="card-body text-center">
                                            <h3 class="mb-0" id="current-stock">0</h3>
                                            <small>Current Stock</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-warning text-white inventory-stat-card">
                                        <div class="card-body text-center">
                                            <h3 class="mb-0" id="stock-value">$0.00</h3>
                                            <small>Stock Value</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="mb-3">Inventory Details</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><strong>Base SKU</strong></td>
                                        <td id="inventory-base-sku">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Base Barcode</strong></td>
                                        <td id="inventory-base-barcode">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Base Unit</strong></td>
                                        <td id="inventory-base-unit">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cost per Unit</strong></td>
                                        <td id="inventory-cost">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stock Status</strong></td>
                                        <td id="inventory-status">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Margin Percentage</strong></td>
                                        <td id="inventory-margin">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Is Expired</strong></td>
                                        <td id="inventory-expired">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Days Until Expiry</strong></td>
                                        <td id="inventory-days-expiry">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Updated</strong></td>
                                        <td id="inventory-updated">-</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>                    <!-- Sales Tab -->
                        <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card bg-success text-white inventory-stat-card">
                                        <div class="card-body text-center">
                                            <h4 class="mb-0" id="total-sold">0</h4>
                                            <small>Total Sold</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-info text-white inventory-stat-card">
                                        <div class="card-body text-center">
                                            <h4 class="mb-0" id="total-revenue">$0.00</h4>
                                            <small>Total Revenue</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-warning text-white inventory-stat-card">
                                        <div class="card-body text-center">
                                            <h4 class="mb-0" id="avg-sale-price">$0.00</h4>
                                            <small>Avg. Sale Price</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h6 class="mb-3">Recent Sales</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody id="recent-sales">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            No recent sales found
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Unit Modal -->
    <div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unitModalLabel">
                        <i class="fas fa-layer-group"></i> <span id="unit-modal-title">Add New Unit</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="unitForm" novalidate>
                    <div class="modal-body">
                        <input type="hidden" id="unit-id" name="unit_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit-name" class="form-label">
                                        <i class="fas fa-tag"></i> Unit Name *
                                    </label>
                                    <input type="text" class="form-control" id="unit-name" name="unit_name"
                                           placeholder="e.g., piece, box, carton" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit-sku" class="form-label">
                                        <i class="fas fa-code"></i> SKU
                                    </label>
                                    <input type="text" class="form-control" id="unit-sku" name="sku"
                                           placeholder="e.g., PROD-001-BOX">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit-barcode" class="form-label">
                                        <i class="fas fa-barcode"></i> Barcode
                                    </label>
                                    <input type="text" class="form-control" id="unit-barcode" name="barcode"
                                           placeholder="e.g., 1234567890123">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit-conversion-rate" class="form-label">
                                        <i class="fas fa-exchange-alt"></i> Conversion Rate *
                                    </label>
                                    <input type="number" class="form-control" id="unit-conversion-rate"
                                           name="conversion_rate" step="0.01" min="0.01" placeholder="1.0" required>
                                    <div class="form-text">How many base units equal 1 of this unit</div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit-selling-price" class="form-label">
                                        <i class="fas fa-dollar-sign"></i> Selling Price *
                                    </label>
                                    <input type="number" class="form-control" id="unit-selling-price"
                                           name="selling_price" step="0.01" min="0" placeholder="0.00" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="unit-is-base"
                                               name="is_base_unit">
                                        <label class="form-check-label" for="unit-is-base">
                                            <i class="fas fa-star"></i> Set as Base Unit
                                        </label>
                                        <div class="form-text">Base unit is the primary unit for this product</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="save-unit-btn">
                            <i class="fas fa-save"></i> <span id="save-unit-text">Save Unit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Unit Confirmation Modal -->
    <div class="modal fade" id="deleteUnitModal" tabindex="-1" aria-labelledby="deleteUnitModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteUnitModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirm Delete Unit
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the unit "<strong id="delete-unit-name"></strong>"?</p>
                    <p class="text-danger mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-unit-btn">
                        <i class="fas fa-trash"></i> Delete Unit
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
