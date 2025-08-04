@extends('layouts.app')

@section('title', 'Product Details')

@push('scripts')
    <script type="module" src="{{ asset('/js/views/products/show.js') }}"></script>
    <script>
        window.productId = {{ $id ?? 'null' }};
    </script>
@endpush

@section('content')
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
            <div class="card">
                <div class="card-body">
                    <!-- Main Image -->
                    <div class="text-center mb-3">
                        <img id="main-image" src="https://via.placeholder.com/400x300"
                             class="img-fluid rounded" alt="Product Image" style="max-height: 400px;">
                    </div>
                </div>
            </div>

            <!-- Product Stats -->
            <div class="card mt-3">
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
            <div class="card">
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
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-tag"></i> Name:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <h5 class="mb-0" id="product-name">Loading...</h5>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-code"></i> Code:</strong>
                                </div>
                                <div class="col-sm-9" id="product-code">Loading...</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-barcode"></i> Barcode:</strong>
                                </div>
                                <div class="col-sm-9" id="product-barcode">Loading...</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-layer-group"></i> Category:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-primary" id="product-category">Loading...</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-dollar-sign"></i> Price:</strong>
                                </div>
                                <div class="col-sm-9" id="product-price">
                                    <h4 class="text-primary mb-0">Loading...</h4>
                                    <small class="text-muted">Loading...</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-toggle-on"></i> Status:</strong>
                                </div>
                                <div class="col-sm-9" id="product-status">
                                    Loading...
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-align-left"></i> Description:</strong>
                                </div>
                                <div class="col-sm-9" id="product-description">
                                    Loading...
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-boxes"></i> Stock:</strong>
                                </div>
                                <div class="col-sm-9" id="product-stock">Loading...</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-calendar-times"></i> Expiry:</strong>
                                </div>
                                <div class="col-sm-9" id="product-expiry">Loading...</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong><i class="fas fa-calendar"></i> Created:</strong>
                                </div>
                                <div class="col-sm-9" id="product-created">Loading...</div>
                            </div>
                        </div>

                        <!-- Inventory Tab -->
                        <!-- Inventory Tab -->
                        <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h3 class="mb-0" id="current-stock">0</h3>
                                            <small>Current Stock</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-warning text-white">
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
                                        <td><strong>Barcode</strong></td>
                                        <td id="inventory-barcode">-</td>
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
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h4 class="mb-0" id="total-sold">0</h4>
                                            <small>Total Sold</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h4 class="mb-0" id="total-revenue">$0.00</h4>
                                            <small>Total Revenue</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-warning text-white">
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
@endsection
