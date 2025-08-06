@extends('layouts.app')

@section('title', 'Edit Product')


@push('scripts')
    <script type="module" src="{{ asset('js/views/products/edit.js') }}"></script>
    <script>
        window.productId = {{ $id ?? 'null' }};
        // Enhanced new image preview functionality (fallback for older browsers)
        if (!window.FileReader) {
            document.getElementById('new_images').addEventListener('change', function (e) {
                console.warn('File preview not supported in this browser');
            });
        }
    </script>
@endpush

@section('content')
    <!-- Loading overlay -->
    <div id="loading-overlay" class="d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-edit"></i> Edit Product
                </h1>
                <div>
                    <a href="#" id="view-product-link" class="btn btn-info me-2">
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
            <form id="editProductForm" method="POST">
                @csrf
                @method('PUT')
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
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left"></i> Description
                                    </label>
                                    <textarea class="form-control" id="description" name="description" rows="5"
                                              placeholder="Enter product description"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">
                                            <i class="fas fa-layer-group"></i> Category *
                                        </label>
                                        <select class="form-select" id="category" name="category_id" required>
                                            <option value="">Select Category</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="expiry_date" class="form-label">
                                            <i class="fas fa-calendar-times"></i> Expiry Date
                                        </label>
                                        <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cost_price" class="form-label">
                                            <i class="fas fa-money-bill"></i> Cost Price *
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control currency-input" id="cost_price"
                                                   name="cost"
                                                   step="0.01" min="0" placeholder="0.00" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="stock" class="form-label">
                                            <i class="fas fa-boxes"></i> Stock Quantity *
                                        </label>
                                        <input type="number" class="form-control" id="stock" name="stock"
                                               min="0" placeholder="0" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="min_stock" class="form-label">
                                            <i class="fas fa-exclamation-triangle"></i> Min Stock Warning *
                                        </label>
                                        <input type="number" class="form-control" id="min_stock" name="min_stock"
                                               min="0" placeholder="0" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Base Unit Information -->
                        <div class="card mb-4 base-unit-info">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle"></i> Current Base Unit Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    This information is read-only. Use the Units management feature to modify units.
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-star"></i> Base Unit
                                        </label>
                                        <div class="form-control-plaintext border rounded p-2 bg-light">
                                            <span id="display-base-unit">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">
                                            <i class="fas fa-dollar-sign"></i> Unit Price *
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="text" class="form-control" id="price" name="price"
                                                   step="0.01" min="0" placeholder="0.00" required disabled>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-code"></i> Base Unit SKU
                                        </label>
                                        <div class="form-control-plaintext border rounded p-2 bg-light">
                                            <code id="display-base-sku">Loading...</code>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-barcode"></i> Base Unit Barcode
                                        </label>
                                        <div class="form-control-plaintext border rounded p-2 bg-light">
                                            <span id="display-base-barcode">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-percentage"></i> Margin Percentage
                                        </label>
                                        <div class="form-control-plaintext border rounded p-2 bg-light">
                                            <span id="display-margin-percentage">Loading...</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-chart-bar"></i> Stock Status
                                        </label>
                                        <div class="form-control-plaintext border rounded p-2 bg-light">
                                            <span id="display-stock-status">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-calendar-times"></i> Expiry Status
                                        </label>
                                        <div class="form-control-plaintext border rounded p-2 bg-light">
                                            <span id="display-expiry-status">Loading...</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <a href="#" id="manage-units-link" class="btn btn-outline-primary">
                                            <i class="fas fa-layer-group"></i> Manage Product Units
                                        </a>
                                    </div>
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
                                <div class="row mb-3" id="current-images-container">
                                    <!-- Images will be loaded dynamically -->
                                    <div class="col-12">
                                        <div class="text-center py-4">
                                            <div class="spinner-border text-muted" role="status">
                                                <span class="visually-hidden">Loading images...</span>
                                            </div>
                                            <p class="text-muted mt-2">Loading product images...</p>
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
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active">
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-eye"></i> Active (Visible to customers)
                                    </label>
                                </div>

                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        Changes to product status will be effective immediately.
                                    </small>
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
                                        <h6 class="text-primary" id="total-sales">-</h6>
                                        <small class="text-muted">Total Sales</small>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="text-success" id="page-views">-</h6>
                                        <small class="text-muted">Page Views</small>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-12">
                                        <small class="text-muted">Created: Loading...</small><br>
                                        <small class="text-muted">Last Updated: Loading...</small>
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
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
                                        <i class="fas fa-trash"></i> Delete Product
                                    </button>
                                    <a href="#" id="cancel-edit-link" class="btn btn-outline-info">
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


@push('styles')
    <style>
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .card {
            transition: box-shadow 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .btn {
            transition: all 0.15s ease-in-out;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        #new-image-preview .card {
            transition: transform 0.2s ease;
        }

        #new-image-preview .card:hover {
            transform: translateY(-2px);
        }

        .invalid-feedback {
            display: block;
        }

        .text-success {
            color: #198754 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        /* Image upload area styling */
        #new_images {
            border: 2px dashed #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            transition: border-color 0.15s ease-in-out;
        }

        #new_images:hover {
            border-color: #0d6efd;
        }

        #new_images:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Base Unit Display Styling */
        .form-control-plaintext {
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            font-weight: 500;
        }

        .base-unit-info .card {
            border-left: 4px solid #0d6efd;
        }

        .base-unit-info .badge {
            font-size: 0.85em;
        }

        #manage-units-link {
            transition: all 0.3s ease;
        }

        #manage-units-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
