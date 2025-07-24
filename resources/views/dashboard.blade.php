@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </h1>
            <small class="text-muted">Welcome back, John Doe!</small>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total Users</h5>
                        <h2 class="mb-0">1,234</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-primary border-0">
                <a href="{{ route('users.index') }}" class="text-white text-decoration-none">
                    View all <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Products</h5>
                        <h2 class="mb-0">567</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-box fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-success border-0">
                <a href="{{ route('products.index') }}" class="text-white text-decoration-none">
                    View all <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Orders</h5>
                        <h2 class="mb-0">89</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-warning border-0">
                <a href="{{ route('orders.index') }}" class="text-white text-decoration-none">
                    View all <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Revenue</h5>
                        <h2 class="mb-0">$12.5K</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-info border-0">
                <span class="text-white">This month</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('users.create') }}" class="btn btn-outline-primary w-100 p-3">
                            <i class="fas fa-user-plus fa-2x d-block mb-2"></i>
                            Add New User
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('products.create') }}" class="btn btn-outline-success w-100 p-3">
                            <i class="fas fa-plus-square fa-2x d-block mb-2"></i>
                            Add New Product
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('orders.create') }}" class="btn btn-outline-warning w-100 p-3">
                            <i class="fas fa-cart-plus fa-2x d-block mb-2"></i>
                            Create Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock"></i> Recent Users
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex align-items-center">
                        <div class="avatar me-3">
                            <img src="https://via.placeholder.com/40" class="rounded-circle" alt="User">
                        </div>
                        <div>
                            <h6 class="mb-0">John Smith</h6>
                            <small class="text-muted">john@example.com</small>
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="avatar me-3">
                            <img src="https://via.placeholder.com/40" class="rounded-circle" alt="User">
                        </div>
                        <div>
                            <h6 class="mb-0">Jane Doe</h6>
                            <small class="text-muted">jane@example.com</small>
                        </div>
                    </div>
                    <div class="list-group-item d-flex align-items-center">
                        <div class="avatar me-3">
                            <img src="https://via.placeholder.com/40" class="rounded-circle" alt="User">
                        </div>
                        <div>
                            <h6 class="mb-0">Mike Johnson</h6>
                            <small class="text-muted">mike@example.com</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shopping-bag"></i> Recent Orders
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Order #1001</h6>
                            <small class="text-muted">John Smith - $299.99</small>
                        </div>
                        <span class="badge bg-success">Completed</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Order #1002</h6>
                            <small class="text-muted">Jane Doe - $199.50</small>
                        </div>
                        <span class="badge bg-warning">Pending</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Order #1003</h6>
                            <small class="text-muted">Mike Johnson - $499.99</small>
                        </div>
                        <span class="badge bg-info">Processing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
