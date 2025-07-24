@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-edit"></i> Edit Order #1001
            </h1>
            <div>
                <a href="{{ route('orders.show', 1001) }}" class="btn btn-info me-2">
                    <i class="fas fa-eye"></i> View
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <form>
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Order Status & Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order_status" class="form-label">Order Status</label>
                            <select class="form-select" id="order_status" name="order_status">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered" selected>Delivered</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-select" id="payment_status" name="payment_status">
                                <option value="pending">Pending</option>
                                <option value="paid" selected>Paid</option>
                                <option value="failed">Failed</option>
                                <option value="refunded">Refunded</option>
                                <option value="partially_refunded">Partially Refunded</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="shipped_at" class="form-label">Shipped Date</label>
                            <input type="datetime-local" class="form-control" id="shipped_at" name="shipped_at" 
                                   value="2025-07-25T10:20">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="delivered_at" class="form-label">Delivered Date</label>
                            <input type="datetime-local" class="form-control" id="delivered_at" name="delivered_at" 
                                   value="2025-07-26T15:45">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tracking_number" class="form-label">Tracking Number</label>
                            <input type="text" class="form-control" id="tracking_number" name="tracking_number" 
                                   value="1Z999AA1234567890">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="carrier" class="form-label">Carrier</label>
                            <select class="form-select" id="carrier" name="carrier">
                                <option value="ups" selected>UPS</option>
                                <option value="fedex">FedEx</option>
                                <option value="dhl">DHL</option>
                                <option value="usps">USPS</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Customer" width="50">
                            <div>
                                <h6 class="mb-0">John Smith</h6>
                                <small class="text-muted">john.smith@example.com | +1 (555) 123-4567</small><br>
                                <small class="text-muted">Customer since: January 2024 | Total orders: 12</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary ms-auto">
                                <i class="fas fa-user-edit"></i> Change Customer
                            </button>
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
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/50" class="rounded me-3" alt="Product" width="50">
                                            <div>
                                                <h6 class="mb-0">Wireless Bluetooth Headphones</h6>
                                                <small class="text-muted">SKU: WBH-001</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="input-group input-group-sm" style="width: 120px; margin: 0 auto;">
                                            <button class="btn btn-outline-secondary" type="button">-</button>
                                            <input type="number" class="form-control text-center" value="2" min="1">
                                            <button class="btn btn-outline-secondary" type="button">+</button>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <input type="number" class="form-control form-control-sm text-end" 
                                               value="199.99" step="0.01" style="width: 100px; margin-left: auto;">
                                    </td>
                                    <td class="text-end fw-bold">$399.98</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Remove">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/50" class="rounded me-3" alt="Product" width="50">
                                            <div>
                                                <h6 class="mb-0">Gaming Mouse Pad</h6>
                                                <small class="text-muted">SKU: GMP-001</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="input-group input-group-sm" style="width: 120px; margin: 0 auto;">
                                            <button class="btn btn-outline-secondary" type="button">-</button>
                                            <input type="number" class="form-control text-center" value="1" min="1">
                                            <button class="btn btn-outline-secondary" type="button">+</button>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <input type="number" class="form-control form-control-sm text-end" 
                                               value="29.99" step="0.01" style="width: 100px; margin-left: auto;">
                                    </td>
                                    <td class="text-end fw-bold">$29.99</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Remove">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                                <textarea class="form-control" rows="4" name="shipping_address">123 Main Street
Apartment 4B
New York, NY 10001
United States</textarea>
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
                                <textarea class="form-control" rows="4" name="billing_address" disabled>123 Main Street
Apartment 4B
New York, NY 10001
United States</textarea>
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
                        <label for="internal_notes" class="form-label">Internal Notes</label>
                        <textarea class="form-control" id="internal_notes" name="internal_notes" 
                                  rows="3">Customer requested express shipping. VIP customer - priority handling.</textarea>
                        <small class="form-text text-muted">These notes are only visible to staff</small>
                    </div>
                    <div class="mb-3">
                        <label for="customer_notes" class="form-label">Customer Notes</label>
                        <textarea class="form-control" id="customer_notes" name="customer_notes" 
                                  rows="3">Please leave package at front door if no one is home. Thanks!</textarea>
                        <small class="form-text text-muted">These notes will be visible to the customer</small>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Order Summary & Actions -->
    <div class="col-md-4">
        <!-- Order Summary -->
        <div class="card mb-4 sticky-top" style="top: 20px;">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calculator"></i> Order Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span id="subtotal">$429.97</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax (8.5%):</span>
                    <input type="number" class="form-control form-control-sm text-end" 
                           value="36.55" step="0.01" style="width: 80px; margin-left: auto;">
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <input type="number" class="form-control form-control-sm text-end" 
                           value="15.00" step="0.01" style="width: 80px; margin-left: auto;">
                </div>
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Discount:</span>
                    <input type="number" class="form-control form-control-sm text-end text-success" 
                           value="-35.55" step="0.01" style="width: 80px; margin-left: auto;">
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold h5">
                    <span>Total:</span>
                    <span id="total" class="text-primary">$445.97</span>
                </div>

                <div class="mt-4">
                    <div class="mb-3">
                        <label for="shipping_method" class="form-label">Shipping Method</label>
                        <select class="form-select" id="shipping_method" name="shipping_method">
                            <option value="standard">Standard Shipping (5-7 days) - Free</option>
                            <option value="express" selected>Express Shipping (2-3 days) - $15.00</option>
                            <option value="overnight">Overnight Shipping (1 day) - $25.00</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="cash">Cash</option>
                            <option value="card" selected>Credit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Order
                    </button>
                    <button type="button" class="btn btn-outline-success">
                        <i class="fas fa-envelope"></i> Send Update Email
                    </button>
                    <button type="button" class="btn btn-outline-info">
                        <i class="fas fa-print"></i> Print Invoice
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i class="fas fa-times"></i> Cancel Order
                    </button>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i> Order Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Order Date:</strong><br>
                    <small class="text-muted">July 24, 2025 at 2:30 PM</small>
                </div>
                <div class="mb-2">
                    <strong>Last Updated:</strong><br>
                    <small class="text-muted">July 26, 2025 at 3:45 PM</small>
                </div>
                <div class="mb-2">
                    <strong>Created by:</strong><br>
                    <small class="text-muted">Customer (Online Order)</small>
                </div>
                <div class="mb-2">
                    <strong>Last Modified by:</strong><br>
                    <small class="text-muted">Admin User</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning"></i> Cancel Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this order?</p>
                <div class="mb-3">
                    <label for="cancellation_reason" class="form-label">Cancellation Reason</label>
                    <select class="form-select" id="cancellation_reason">
                        <option value="customer_request">Customer Request</option>
                        <option value="payment_failed">Payment Failed</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="fraud_suspected">Fraud Suspected</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="cancellation_notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="cancellation_notes" rows="3" 
                              placeholder="Additional details about the cancellation..."></textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="notify_customer">
                    <label class="form-check-label" for="notify_customer">
                        Send cancellation email to customer
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                <button type="button" class="btn btn-danger">
                    <i class="fas fa-times"></i> Cancel Order
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="fas fa-plus"></i> Add Product to Order
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
@endsection

@section('scripts')
<script>
    // Toggle billing address
    document.getElementById('same_as_shipping').addEventListener('change', function() {
        const billingAddress = document.querySelector('textarea[name="billing_address"]');
        const shippingAddress = document.querySelector('textarea[name="shipping_address"]');
        
        if (this.checked) {
            billingAddress.disabled = true;
            billingAddress.value = shippingAddress.value;
        } else {
            billingAddress.disabled = false;
        }
    });

    // Quantity change handlers
    document.querySelectorAll('.input-group button').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input[type="number"]');
            const isIncrement = this.textContent === '+';
            const currentValue = parseInt(input.value);
            
            if (isIncrement) {
                input.value = currentValue + 1;
            } else if (currentValue > 1) {
                input.value = currentValue - 1;
            }
            
            updateLineTotal(input);
        });
    });

    function updateLineTotal(quantityInput) {
        const row = quantityInput.closest('tr');
        const priceInput = row.querySelector('input[type="number"]:not([min])');
        const totalCell = row.querySelector('.fw-bold');
        
        const quantity = parseInt(quantityInput.value);
        const price = parseFloat(priceInput.value);
        const total = quantity * price;
        
        totalCell.textContent = '$' + total.toFixed(2);
        updateOrderTotal();
    }

    function updateOrderTotal() {
        // Calculate and update order totals
        console.log('Updating order total...');
    }
</script>
@endsection
