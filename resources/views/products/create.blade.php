@extends('layouts.app')

@section('title', 'Create Product')

@push('scripts')
    <script type="module" src="{{ asset('js/views/products/create.js') }}"></script>
@endpush

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

    <!-- Loading overlay -->
    <div id="loading-overlay" class="d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <form id="createProductForm" method="POST">
                @csrf
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
                                        <i class="fas fa-align-left"></i> Description *
                                    </label>
                                    <textarea class="form-control" id="description" name="description" rows="5"
                                              placeholder="Enter product description" required></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="base_sku" class="form-label">
                                            <i class="fas fa-barcode"></i> Base SKU *
                                        </label>
                                        <input type="text" class="form-control" id="base_sku" name="base_sku"
                                               placeholder="e.g., SP-001" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="base_unit" class="form-label">
                                            <i class="fas fa-ruler"></i> Base Unit *
                                        </label>
                                        <input type="text" class="form-control" id="base_unit" name="base_unit"
                                               placeholder="e.g., piece, kg, liter" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">
                                            <i class="fas fa-layer-group"></i> Category *
                                        </label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="">Select Category</option>
                                            <!-- Categories will be loaded dynamically -->
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="barcode" class="form-label">
                                            <i class="fas fa-qrcode"></i> Barcode
                                        </label>
                                        <input type="text" class="form-control" id="barcode" name="barcode"
                                               placeholder="Enter barcode">
                                        <div class="invalid-feedback"></div>
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
                                        <div class="invalid-feedback"></div>
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
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="stock" class="form-label">
                                            <i class="fas fa-boxes"></i> Stock Quantity *
                                        </label>
                                        <input type="number" class="form-control" id="stock" name="stock"
                                               min="0" placeholder="0" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="min_stock" class="form-label">
                                            <i class="fas fa-exclamation-triangle"></i> Minimum Stock *
                                        </label>
                                        <input type="number" class="form-control" id="min_stock" name="min_stock"
                                               min="0" placeholder="0" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="expiry_date" class="form-label">
                                            <i class="fas fa-calendar-alt"></i> Expiry Date
                                        </label>
                                        <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                                        <div class="invalid-feedback"></div>
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
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                           checked>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-eye"></i> Active (Visible to customers)
                                    </label>
                                </div>

                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        Product code will be auto-generated after creation. Units can be managed after
                                        the product is created.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-search"></i> Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-secondary" id="save-draft-btn">
                                        <i class="fas fa-save"></i> Save as Draft
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus-square"></i> Create Product
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-danger">
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

        #image-preview .card {
            transition: transform 0.2s ease;
        }

        #image-preview .card:hover {
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
        #images {
            border: 2px dashed #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            transition: border-color 0.15s ease-in-out;
        }

        #images:hover {
            border-color: #0d6efd;
        }

        #images:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>
@endpush
