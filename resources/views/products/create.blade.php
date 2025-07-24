@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-plus-square"></i> Create New Product
            </h1>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
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
                                       placeholder="Enter product name" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left"></i> Description
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="5" 
                                          placeholder="Enter product description"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sku" class="form-label">
                                        <i class="fas fa-barcode"></i> SKU *
                                    </label>
                                    <input type="text" class="form-control" id="sku" name="sku" 
                                           placeholder="e.g., PROD-001" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">
                                        <i class="fas fa-layer-group"></i> Category
                                    </label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">Select Category</option>
                                        <option value="electronics">Electronics</option>
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
                                               step="0.01" min="0" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cost_price" class="form-label">
                                        <i class="fas fa-money-bill"></i> Cost Price
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="cost_price" name="cost_price" 
                                               step="0.01" min="0" placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">
                                        <i class="fas fa-boxes"></i> Stock Quantity *
                                    </label>
                                    <input type="number" class="form-control" id="stock" name="stock" 
                                           min="0" placeholder="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="min_stock" class="form-label">
                                        <i class="fas fa-exclamation-triangle"></i> Minimum Stock Level
                                    </label>
                                    <input type="number" class="form-control" id="min_stock" name="min_stock" 
                                           min="0" placeholder="5">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label">
                                        <i class="fas fa-weight"></i> Weight (kg)
                                    </label>
                                    <input type="number" class="form-control" id="weight" name="weight" 
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dimensions" class="form-label">
                                        <i class="fas fa-ruler-combined"></i> Dimensions (L×W×H cm)
                                    </label>
                                    <input type="text" class="form-control" id="dimensions" name="dimensions" 
                                           placeholder="e.g., 10×5×2">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-images"></i> Product Images
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="images" class="form-label">Upload Images</label>
                                <input type="file" class="form-control" id="images" name="images[]" 
                                       accept="image/*" multiple>
                                <small class="form-text text-muted">
                                    You can select multiple images. Maximum 5 images, 2MB each. 
                                    First image will be used as the main product image.
                                </small>
                            </div>
                            <div id="image-preview" class="row"></div>
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
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured">
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
                                       placeholder="SEO title">
                                <small class="form-text text-muted">Recommended: 50-60 characters</small>
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" 
                                          rows="3" placeholder="SEO description"></textarea>
                                <small class="form-text text-muted">Recommended: 150-160 characters</small>
                            </div>

                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="tags" name="tags" 
                                       placeholder="tag1, tag2, tag3">
                                <small class="form-text text-muted">Separate tags with commas</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bolt"></i> Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-save"></i> Save as Draft
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus-square"></i> Create Product
                                </button>
                                <button type="button" class="btn btn-outline-danger">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Image preview functionality
    document.getElementById('images').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = '';

        for (let i = 0; i < Math.min(files.length, 5); i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-3';
                col.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-muted">${file.name}</small>
                            ${i === 0 ? '<br><small class="text-primary">Main Image</small>' : ''}
                        </div>
                    </div>
                `;
                previewContainer.appendChild(col);
            };

            reader.readAsDataURL(file);
        }
    });

    // Auto-generate SKU from product name
    document.getElementById('name').addEventListener('input', function(e) {
        const skuField = document.getElementById('sku');
        if (!skuField.value) {
            const sku = e.target.value
                .replace(/[^a-zA-Z0-9\s]/g, '')
                .replace(/\s+/g, '-')
                .toUpperCase()
                .substring(0, 20);
            skuField.value = sku;
        }
    });
</script>
@endsection
