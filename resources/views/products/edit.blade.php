@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-edit"></i> Edit Product
            </h1>
            <div>
                <a href="{{ route('products.show', 1) }}" class="btn btn-info me-2">
                    <i class="fas fa-eye"></i> View
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <form>
            <div class="row">
                <!-- Product Information -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle"></i> Product Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-tag"></i> Product Name *
                                </label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="Wireless Bluetooth Headphones" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left"></i> Description
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="5">Premium quality wireless Bluetooth headphones with active noise cancellation technology. Features high-quality drivers for crystal clear audio and comfortable over-ear design for extended listening sessions. Battery life up to 30 hours with quick charge capability.</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sku" class="form-label">
                                        <i class="fas fa-barcode"></i> SKU *
                                    </label>
                                    <input type="text" class="form-control" id="sku" name="sku" 
                                           value="WBH-001" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">
                                        <i class="fas fa-layer-group"></i> Category
                                    </label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">Select Category</option>
                                        <option value="electronics" selected>Electronics</option>
                                        <option value="clothing">Clothing</option>
                                        <option value="books">Books</option>
                                        <option value="home">Home & Garden</option>
                                        <option value="sports">Sports</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">
                                        <i class="fas fa-dollar-sign"></i> Price *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="price" name="price" 
                                               step="0.01" min="0" value="199.99" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cost_price" class="form-label">
                                        <i class="fas fa-money-bill"></i> Cost Price
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="cost_price" name="cost_price" 
                                               step="0.01" min="0" value="120.00">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">
                                        <i class="fas fa-boxes"></i> Stock Quantity *
                                    </label>
                                    <input type="number" class="form-control" id="stock" name="stock" 
                                           min="0" value="25" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="min_stock" class="form-label">
                                        <i class="fas fa-exclamation-triangle"></i> Minimum Stock Level
                                    </label>
                                    <input type="number" class="form-control" id="min_stock" name="min_stock" 
                                           min="0" value="5">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">
                                        <i class="fas fa-weight"></i> Weight (kg)
                                    </label>
                                    <input type="number" class="form-control" id="weight" name="weight" 
                                           step="0.01" min="0" value="0.25">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dimensions" class="form-label">
                                        <i class="fas fa-ruler-combined"></i> Dimensions (L×W×H cm)
                                    </label>
                                    <input type="text" class="form-control" id="dimensions" name="dimensions" 
                                           value="20×15×8">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="tags" class="form-label">
                                    <i class="fas fa-tags"></i> Tags
                                </label>
                                <input type="text" class="form-control" id="tags" name="tags" 
                                       value="wireless, bluetooth, headphones, noise-cancellation">
                                <small class="form-text text-muted">Separate tags with commas</small>
                            </div>
                        </div>
                    </div>

                    <!-- Current Images -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-images"></i> Current Images
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="https://via.placeholder.com/200x150" class="card-img-top" alt="Product Image 1">
                                        <div class="card-body p-2 text-center">
                                            <small class="text-primary">Main Image</small><br>
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-1">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="https://via.placeholder.com/200x150" class="card-img-top" alt="Product Image 2">
                                        <div class="card-body p-2 text-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-1">
                                                <i class="fas fa-star"></i> Make Main
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-1">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="https://via.placeholder.com/200x150" class="card-img-top" alt="Product Image 3">
                                        <div class="card-body p-2 text-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-1">
                                                <i class="fas fa-star"></i> Make Main
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-1">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="new_images" class="form-label">Add New Images</label>
                                <input type="file" class="form-control" id="new_images" name="new_images[]" 
                                       accept="image/*" multiple>
                                <small class="form-text text-muted">
                                    You can add more images. Maximum 5 images total, 2MB each.
                                </small>
                            </div>
                            <div id="new-image-preview" class="row"></div>
                        </div>
                    </div>
                </div>

                <!-- Product Settings -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cog"></i> Product Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-eye"></i> Active (Visible to customers)
                                </label>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" checked>
                                <label class="form-check-label" for="is_featured">
                                    <i class="fas fa-star"></i> Featured Product
                                </label>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="track_stock" name="track_stock" checked>
                                <label class="form-check-label" for="track_stock">
                                    <i class="fas fa-chart-line"></i> Track Stock
                                </label>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="allow_backorder" name="allow_backorder">
                                <label class="form-check-label" for="allow_backorder">
                                    <i class="fas fa-clock"></i> Allow Backorders
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-search"></i> SEO Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                       value="Wireless Bluetooth Headphones - Premium Audio">
                                <small class="form-text text-muted">Recommended: 50-60 characters</small>
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" 
                                          rows="3">Premium quality wireless Bluetooth headphones with active noise cancellation. 30-hour battery life and crystal clear audio.</textarea>
                                <small class="form-text text-muted">Recommended: 150-160 characters</small>
                            </div>
                        </div>
                    </div>

                    <!-- Product Statistics -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar"></i> Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <h6 class="text-primary">152</h6>
                                    <small class="text-muted">Total Sales</small>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-success">87</h6>
                                    <small class="text-muted">Page Views</small>
                                </div>
                            </div>
                            <div class="row text-center">
                                <div class="col-12">
                                    <small class="text-muted">Created: Jan 20, 2025</small><br>
                                    <small class="text-muted">Last Updated: Jul 24, 2025</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bolt"></i> Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Product
                                </button>
                                <button type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-copy"></i> Duplicate Product
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="fas fa-trash"></i> Delete Product
                                </button>
                                <a href="{{ route('products.show', 1) }}" class="btn btn-outline-info">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this product? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> All associated data (sales history, reviews) will also be deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Product
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // New image preview functionality
    document.getElementById('new_images').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.getElementById('new-image-preview');
        previewContainer.innerHTML = '';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-3';
                col.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2 text-center">
                            <small class="text-success">New Image</small><br>
                            <small class="text-muted">${file.name}</small>
                        </div>
                    </div>
                `;
                previewContainer.appendChild(col);
            };

            reader.readAsDataURL(file);
        }
    });

    // Calculate profit margin
    function calculateMargin() {
        const price = parseFloat(document.getElementById('price').value) || 0;
        const cost = parseFloat(document.getElementById('cost_price').value) || 0;
        
        if (price > 0 && cost > 0) {
            const margin = ((price - cost) / price * 100).toFixed(1);
            console.log(`Margin: ${margin}%`);
        }
    }

    document.getElementById('price').addEventListener('input', calculateMargin);
    document.getElementById('cost_price').addEventListener('input', calculateMargin);
</script>
@endsection
