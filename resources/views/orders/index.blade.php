@extends('layouts.app')

@section('title', 'Orders Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-shopping-cart"></i> Orders Management
            </h1>
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Order
            </a>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Search by order number or customer...">
            <button class="btn btn-outline-secondary" type="button">Search</button>
        </div>
    </div>
    <div class="col-md-2">
        <select class="form-select">
            <option selected>All Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-text">From</span>
            <input type="date" class="form-control" value="2025-01-01">
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-text">To</span>
            <input type="date" class="form-control" value="2025-07-24">
        </div>
    </div>
</div>

<!-- Order Statistics -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">89</h4>
                        <small>Total Orders</small>
                    </div>
                    <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">$12,540</h4>
                        <small>Total Revenue</small>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">15</h4>
                        <small>Pending Orders</small>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">$140</h4>
                        <small>Avg. Order Value</small>
                    </div>
                    <i class="fas fa-chart-bar fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Recent Orders</h5>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv"></i> Export as CSV</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel"></i> Export as Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf"></i> Export as PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', 1001) }}" class="fw-bold text-decoration-none">
                                #1001
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="Customer" width="32">
                                <div>
                                    <h6 class="mb-0">John Smith</h6>
                                    <small class="text-muted">john@example.com</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span>Jul 24, 2025</span><br>
                                <small class="text-muted">2:30 PM</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">3 items</span>
                        </td>
                        <td class="fw-bold text-success">$299.99</td>
                        <td>
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> Delivered
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success">Paid</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('orders.show', 1001) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('orders.edit', 1001) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Print Invoice">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', 1002) }}" class="fw-bold text-decoration-none">
                                #1002
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="Customer" width="32">
                                <div>
                                    <h6 class="mb-0">Jane Doe</h6>
                                    <small class="text-muted">jane@example.com</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span>Jul 23, 2025</span><br>
                                <small class="text-muted">10:15 AM</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">1 item</span>
                        </td>
                        <td class="fw-bold text-success">$199.50</td>
                        <td>
                            <span class="badge bg-info">
                                <i class="fas fa-truck"></i> Shipped
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success">Paid</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('orders.show', 1002) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('orders.edit', 1002) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Print Invoice">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', 1003) }}" class="fw-bold text-decoration-none">
                                #1003
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="Customer" width="32">
                                <div>
                                    <h6 class="mb-0">Mike Johnson</h6>
                                    <small class="text-muted">mike@example.com</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span>Jul 22, 2025</span><br>
                                <small class="text-muted">4:45 PM</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">5 items</span>
                        </td>
                        <td class="fw-bold text-success">$499.99</td>
                        <td>
                            <span class="badge bg-warning">
                                <i class="fas fa-cog"></i> Processing
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success">Paid</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('orders.show', 1003) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('orders.edit', 1003) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Print Invoice">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="{{ route('orders.show', 1004) }}" class="fw-bold text-decoration-none">
                                #1004
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="Customer" width="32">
                                <div>
                                    <h6 class="mb-0">Sarah Wilson</h6>
                                    <small class="text-muted">sarah@example.com</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span>Jul 21, 2025</span><br>
                                <small class="text-muted">1:20 PM</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">2 items</span>
                        </td>
                        <td class="fw-bold text-success">$149.98</td>
                        <td>
                            <span class="badge bg-secondary">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-warning">Pending</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('orders.show', 1004) }}" class="btn btn-sm btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('orders.edit', 1004) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Cancel Order">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0 justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
