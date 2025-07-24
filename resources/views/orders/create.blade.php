@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-cart-plus"></i> Create New Order
            </h1>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <form>
            <!-- Customer Selection -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="customer" class="form-label">Select Customer *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="customer_search" 
                                       placeholder="Search customer by name or email...">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#customerModal">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Start typing to search for existing customers</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#newCustomerModal">
                                <i class="fas fa-plus"></i> New Customer
                            </button>
                        </div>
                    </div>

                    <!-- Selected Customer Info -->
                    <div id="selected-customer" class="d-none">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Customer" width="50">
                                <div>
                                    <h6 class="mb-0">John Smith</h6>
                                    <small class="text-muted">john.smith@example.com</small><br>
                                    <small class="text-muted">Previous orders: 5 | Total spent: $1,250</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-auto">
                                    <i class="fas fa-times"></i> Change
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shopping-bag"></i> Order Items
                        </h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal">
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="order-items">
                        <!-- Order items will be added here -->
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-shopping-cart fa-3x mb-3 opacity-50"></i>
                            <p>No items added yet. Click "Add Product" to start building the order.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping & Billing -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-truck"></i> Shipping & Billing Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Shipping Address</h6>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Full Address *</label>
                                <textarea class="form-control" id="shipping_address" name="shipping_address" 
                                          rows="4" placeholder="Enter shipping address" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Billing Address</h6>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="same_as_shipping" checked>
                                <label class="form-check-label" for="same_as_shipping">
                                    Same as shipping address
                                </label>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="billing_address" name="billing_address" 
                                          rows="4" placeholder="Enter billing address" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sticky-note"></i> Order Notes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Internal Notes</label>
                        <textarea class="form-control" id="notes" name="notes" 
                                  rows="3" placeholder="Add any internal notes about this order..."></textarea>
                        <small class="form-text text-muted">These notes are only visible to staff</small>
                    </div>
                    <div class="mb-3">
                        <label for="customer_notes" class="form-label">Customer Notes</label>
                        <textarea class="form-control" id="customer_notes" name="customer_notes" 
                                  rows="3" placeholder="Add any notes for the customer..."></textarea>
                        <small class="form-text text-muted">These notes will be visible to the customer</small>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Order Summary -->
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calculator"></i> Order Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span id="subtotal">$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax (8.5%):</span>
                    <span id="tax">$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span id="shipping">$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount:</span>
                    <span id="discount" class="text-success">$0.00</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold h5">
                    <span>Total:</span>
                    <span id="total" class="text-primary">$0.00</span>
                </div>

                <div class="mt-4">
                    <div class="mb-3">
                        <label for="shipping_method" class="form-label">Shipping Method</label>
                        <select class="form-select" id="shipping_method" name="shipping_method">
                            <option value="standard">Standard Shipping (5-7 days) - Free</option>
                            <option value="express">Express Shipping (2-3 days) - $15.00</option>
                            <option value="overnight">Overnight Shipping (1 day) - $25.00</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="cash">Cash</option>
                            <option value="card">Credit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="order_status" class="form-label">Initial Status</label>
                        <select class="form-select" id="order_status" name="order_status">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Create Order
                    </button>
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="fas fa-save"></i> Save as Draft
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Selection Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">
                    <i class="fas fa-search"></i> Select Products
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Search products...">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Wireless Headphones</h6>
                                <p class="card-text text-muted">SKU: WBH-001</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">$199.99</span>
                                    <span class="text-muted">Stock: 25</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary mt-2 w-100">
                                    <i class="fas fa-plus"></i> Add to Order
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Gaming Mouse</h6>
                                <p class="card-text text-muted">SKU: GM-002</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">$79.99</span>
                                    <span class="text-muted">Stock: 15</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary mt-2 w-100">
                                    <i class="fas fa-plus"></i> Add to Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Selection Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalLabel">
                    <i class="fas fa-users"></i> Select Customer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Search customers...">
                </div>
                <div class="list-group">
                    <button type="button" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Customer" width="40">
                            <div>
                                <h6 class="mb-0">John Smith</h6>
                                <small class="text-muted">john.smith@example.com</small>
                            </div>
                        </div>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Customer" width="40">
                            <div>
                                <h6 class="mb-0">Jane Doe</h6>
                                <small class="text-muted">jane.doe@example.com</small>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle billing address
    document.getElementById('same_as_shipping').addEventListener('change', function() {
        const billingAddress = document.getElementById('billing_address');
        const shippingAddress = document.getElementById('shipping_address');
        
        if (this.checked) {
            billingAddress.disabled = true;
            billingAddress.value = shippingAddress.value;
        } else {
            billingAddress.disabled = false;
        }
    });

    // Copy shipping to billing when typing
    document.getElementById('shipping_address').addEventListener('input', function() {
        const billingAddress = document.getElementById('billing_address');
        const sameAsShipping = document.getElementById('same_as_shipping');
        
        if (sameAsShipping.checked) {
            billingAddress.value = this.value;
        }
    });

    // Calculate shipping cost
    document.getElementById('shipping_method').addEventListener('change', function() {
        const shippingSpan = document.getElementById('shipping');
        let shippingCost = 0;
        
        switch(this.value) {
            case 'express':
                shippingCost = 15.00;
                break;
            case 'overnight':
                shippingCost = 25.00;
                break;
        }
        
        shippingSpan.textContent = '$' + shippingCost.toFixed(2);
        updateTotal();
    });

    function updateTotal() {
        // Placeholder function for total calculation
        console.log('Updating order total...');
    }
</script>
@endsection
