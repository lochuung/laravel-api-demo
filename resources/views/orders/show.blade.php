@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-shopping-cart"></i> Order #1001
            </h1>
            <div>
                <button class="btn btn-outline-primary me-2">
                    <i class="fas fa-print"></i> Print Invoice
                </button>
                <a href="{{ route('orders.edit', 1001) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-md-8">
        <!-- Order Header -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Order Information
                    </h5>
                    <div>
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check"></i> Delivered
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Order Date:</strong></div>
                            <div class="col-sm-8">July 24, 2025 at 2:30 PM</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Order Number:</strong></div>
                            <div class="col-sm-8">#1001</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Payment Method:</strong></div>
                            <div class="col-sm-8">
                                <i class="fas fa-credit-card"></i> Credit Card
                                <span class="badge bg-success ms-2">Paid</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Shipped Date:</strong></div>
                            <div class="col-sm-8">July 25, 2025</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Delivered Date:</strong></div>
                            <div class="col-sm-8">July 26, 2025</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4"><strong>Tracking Number:</strong></div>
                            <div class="col-sm-8">
                                <a href="#" class="text-decoration-none">1Z999AA1234567890</a>
                            </div>
                        </div>
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://via.placeholder.com/60" class="rounded-circle me-3" alt="Customer" width="60">
                            <div>
                                <h6 class="mb-0">John Smith</h6>
                                <small class="text-muted">john.smith@example.com</small><br>
                                <small class="text-muted">Phone: +1 (555) 123-4567</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">Customer since: January 2024</small><br>
                                <small class="text-muted">Total orders: 12 | Total spent: $2,847.50</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h6 class="mb-1">Shipping Address</h6>
                                <address class="mb-0">
                                    123 Main Street<br>
                                    Apartment 4B<br>
                                    New York, NY 10001<br>
                                    United States
                                </address>
                            </div>
                            <div class="col-12">
                                <h6 class="mb-1">Billing Address</h6>
                                <address class="mb-0">
                                    <small class="text-muted">Same as shipping address</small>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shopping-bag"></i> Order Items
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/50" class="rounded me-3" alt="Product" width="50">
                                        <div>
                                            <h6 class="mb-0">Wireless Bluetooth Headphones</h6>
                                            <small class="text-muted">Premium noise-cancelling headphones</small>
                                        </div>
                                    </div>
                                </td>
                                <td>WBH-001</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">2</span>
                                </td>
                                <td class="text-end">$199.99</td>
                                <td class="text-end fw-bold">$399.98</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/50" class="rounded me-3" alt="Product" width="50">
                                        <div>
                                            <h6 class="mb-0">Gaming Mouse Pad</h6>
                                            <small class="text-muted">Large RGB gaming mouse pad</small>
                                        </div>
                                    </div>
                                </td>
                                <td>GMP-001</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">1</span>
                                </td>
                                <td class="text-end">$29.99</td>
                                <td class="text-end fw-bold">$29.99</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history"></i> Order Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-check text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">Order Delivered</h6>
                            <small class="text-muted">July 26, 2025 at 3:45 PM</small>
                            <p class="mb-0 text-muted">Package delivered to customer. Signed by: John Smith</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-truck text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">Order Shipped</h6>
                            <small class="text-muted">July 25, 2025 at 10:20 AM</small>
                            <p class="mb-0 text-muted">Tracking: 1Z999AA1234567890</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-cog text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">Order Processing</h6>
                            <small class="text-muted">July 24, 2025 at 4:15 PM</small>
                            <p class="mb-0 text-muted">Order confirmed and being prepared for shipment</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">Payment Confirmed</h6>
                            <small class="text-muted">July 24, 2025 at 2:35 PM</small>
                            <p class="mb-0 text-muted">Payment of $445.97 received via Credit Card</p>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-shopping-cart text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">Order Placed</h6>
                            <small class="text-muted">July 24, 2025 at 2:30 PM</small>
                            <p class="mb-0 text-muted">Order created by customer</p>
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
                    <h6 class="text-muted">Internal Notes:</h6>
                    <p class="mb-0">Customer requested express shipping. VIP customer - priority handling.</p>
                </div>
                <div>
                    <h6 class="text-muted">Customer Notes:</h6>
                    <p class="mb-0">Please leave package at front door if no one is home. Thanks!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary & Actions -->
    <div class="col-md-4">
        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calculator"></i> Order Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>$429.97</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tax (8.5%):</span>
                    <span>$36.55</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span>$15.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Discount:</span>
                    <span>-$35.55</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold h5">
                    <span>Total:</span>
                    <span class="text-primary">$445.97</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary">
                        <i class="fas fa-envelope"></i> Send Email Update
                    </button>
                    <button type="button" class="btn btn-outline-info">
                        <i class="fas fa-undo"></i> Process Refund
                    </button>
                    <button type="button" class="btn btn-outline-success">
                        <i class="fas fa-copy"></i> Duplicate Order
                    </button>
                    <button type="button" class="btn btn-outline-warning">
                        <i class="fas fa-exclamation-triangle"></i> Report Issue
                    </button>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-truck"></i> Shipping Details
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Method:</strong> Express Shipping
                </div>
                <div class="mb-2">
                    <strong>Carrier:</strong> UPS
                </div>
                <div class="mb-2">
                    <strong>Tracking:</strong> 
                    <a href="#" class="text-decoration-none">1Z999AA1234567890</a>
                </div>
                <div class="mb-2">
                    <strong>Weight:</strong> 1.2 kg
                </div>
                <div class="mb-2">
                    <strong>Dimensions:</strong> 25×20×10 cm
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-credit-card"></i> Payment Details
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Method:</strong> Credit Card
                </div>
                <div class="mb-2">
                    <strong>Card:</strong> ****-****-****-4242
                </div>
                <div class="mb-2">
                    <strong>Status:</strong> 
                    <span class="badge bg-success">Paid</span>
                </div>
                <div class="mb-2">
                    <strong>Transaction ID:</strong> txn_1234567890
                </div>
                <div class="mb-2">
                    <strong>Paid on:</strong> July 24, 2025
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
