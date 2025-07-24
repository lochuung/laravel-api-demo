@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-box"></i> Product Details
            </h1>
            <div>
                <a href="{{ route('products.edit', 1) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
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
                
                <!-- Thumbnail Images -->
                <div class="row">
                    <div class="col-3">
                        <img src="https://via.placeholder.com/100x75" 
                             class="img-thumbnail w-100 thumbnail-img active" 
                             data-image="https://via.placeholder.com/400x300" alt="Thumbnail 1">
                    </div>
                    <div class="col-3">
                        <img src="https://via.placeholder.com/100x75" 
                             class="img-thumbnail w-100 thumbnail-img" 
                             data-image="https://via.placeholder.com/400x300" alt="Thumbnail 2">
                    </div>
                    <div class="col-3">
                        <img src="https://via.placeholder.com/100x75" 
                             class="img-thumbnail w-100 thumbnail-img" 
                             data-image="https://via.placeholder.com/400x300" alt="Thumbnail 3">
                    </div>
                    <div class="col-3">
                        <img src="https://via.placeholder.com/100x75" 
                             class="img-thumbnail w-100 thumbnail-img" 
                             data-image="https://via.placeholder.com/400x300" alt="Thumbnail 4">
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Stats -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar"></i> Product Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-primary">152</h5>
                        <small class="text-muted">Total Sales</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success">4.8/5</h5>
                        <small class="text-muted">Rating</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-warning">25</h5>
                        <small class="text-muted">In Stock</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-info">87</h5>
                        <small class="text-muted">Views</small>
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
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" 
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
                    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-tag"></i> Name:</strong>
                            </div>
                            <div class="col-sm-9">
                                <h5 class="mb-0">Wireless Bluetooth Headphones</h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-barcode"></i> SKU:</strong>
                            </div>
                            <div class="col-sm-9">WBH-001</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-layer-group"></i> Category:</strong>
                            </div>
                            <div class="col-sm-9">
                                <span class="badge bg-primary">Electronics</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-dollar-sign"></i> Price:</strong>
                            </div>
                            <div class="col-sm-9">
                                <h4 class="text-primary mb-0">$199.99</h4>
                                <small class="text-muted">Cost: $120.00 | Margin: 40%</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-toggle-on"></i> Status:</strong>
                            </div>
                            <div class="col-sm-9">
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Active
                                </span>
                                <span class="badge bg-warning ms-2">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-align-left"></i> Description:</strong>
                            </div>
                            <div class="col-sm-9">
                                Premium quality wireless Bluetooth headphones with active noise cancellation technology. 
                                Features high-quality drivers for crystal clear audio and comfortable over-ear design 
                                for extended listening sessions. Battery life up to 30 hours with quick charge capability.
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-weight"></i> Weight:</strong>
                            </div>
                            <div class="col-sm-9">0.25 kg</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-ruler-combined"></i> Dimensions:</strong>
                            </div>
                            <div class="col-sm-9">20×15×8 cm</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-tags"></i> Tags:</strong>
                            </div>
                            <div class="col-sm-9">
                                <span class="badge bg-secondary me-1">wireless</span>
                                <span class="badge bg-secondary me-1">bluetooth</span>
                                <span class="badge bg-secondary me-1">headphones</span>
                                <span class="badge bg-secondary me-1">noise-cancellation</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="fas fa-calendar"></i> Created:</strong>
                            </div>
                            <div class="col-sm-9">January 20, 2025</div>
                        </div>
                    </div>

                    <!-- Inventory Tab -->
                    <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">25</h3>
                                        <small>Current Stock</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-0">5</h3>
                                        <small>Minimum Level</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Reason</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Jul 24, 2025</td>
                                        <td><span class="badge bg-danger">Out</span></td>
                                        <td>-2</td>
                                        <td>Sale #1001</td>
                                        <td>25</td>
                                    </tr>
                                    <tr>
                                        <td>Jul 20, 2025</td>
                                        <td><span class="badge bg-success">In</span></td>
                                        <td>+50</td>
                                        <td>Purchase #PO-100</td>
                                        <td>27</td>
                                    </tr>
                                    <tr>
                                        <td>Jul 18, 2025</td>
                                        <td><span class="badge bg-danger">Out</span></td>
                                        <td>-23</td>
                                        <td>Sale #1000</td>
                                        <td>-23</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sales Tab -->
                    <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">152</h4>
                                        <small>Total Sold</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">$30,398</h4>
                                        <small>Total Revenue</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">$200</h4>
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
                                <tbody>
                                    <tr>
                                        <td><a href="{{ route('orders.show', 1001) }}">#1001</a></td>
                                        <td>John Smith</td>
                                        <td>Jul 24, 2025</td>
                                        <td>2</td>
                                        <td>$199.99</td>
                                        <td>$399.98</td>
                                    </tr>
                                    <tr>
                                        <td><a href="{{ route('orders.show', 1000) }}">#1000</a></td>
                                        <td>Jane Doe</td>
                                        <td>Jul 20, 2025</td>
                                        <td>1</td>
                                        <td>$199.99</td>
                                        <td>$199.99</td>
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

@section('scripts')
<script>
    // Thumbnail image functionality
    document.querySelectorAll('.thumbnail-img').forEach(function(thumbnail) {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail-img').forEach(function(thumb) {
                thumb.classList.remove('active');
            });
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Update main image
            const mainImage = document.getElementById('main-image');
            mainImage.src = this.dataset.image;
        });
    });
</script>

<style>
.thumbnail-img {
    cursor: pointer;
    transition: all 0.3s ease;
}

.thumbnail-img:hover {
    opacity: 0.8;
}

.thumbnail-img.active {
    border-color: #0d6efd !important;
    border-width: 2px !important;
}
</style>
@endsection
